<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Rector\CodingStyle\Rector\Stmt;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeFinder;
use Rector\PhpParser\Node\FileNode;
use Rector\Rector\AbstractRector;
use RectorPrefix202512\Nette\Utils\Strings;
use Symplify\RuleDocGenerator\Exception\PoorDocumentationException;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

use function count;
use function file_put_contents;
use function is_array;
use function preg_match;
use function strtolower;

use const false;
use const true;

final class RemoveNonConflictingAliasInUseStatementRector extends AbstractRector
{
    /**
     * @throws PoorDocumentationException
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove non-conflicting alias in use statement when an alias exists for no conflicting reason', [
            new CodeSample(
                <<<'CODE_SAMPLE'
                    use App\Bar as AppBar;
                    CODE_SAMPLE,
                <<<'CODE_SAMPLE'
                    use App\Bar;
                    CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FileNode::class, Namespace_::class];
    }

    public function refactor(Node $node): FileNode|Node|Namespace_|null
    {
        if ($node instanceof FileNode && $node->isNamespaced()) {
            // handle in Namespace_ node
            return null;
        }
        $hasChanged = false;

        foreach ($node->stmts as $stmt) {
            if (! $stmt instanceof Use_) {
                continue;
            }

            if (count($stmt->uses) !== 1) {
                continue;
            }

            if (! isset($stmt->uses[0])) {
                continue;
            }
            $aliasName = $stmt->uses[0]->alias instanceof Identifier ? $stmt->uses[0]->alias->toString() : null;

            if ($aliasName === null) {
                continue;
            }

            $useName          = $stmt->uses[0]->name->toString();
            $aliasUseLastName = Strings::after($useName, '\\', -1) ?? $useName;

            foreach ($node->stmts as $compareStmt) {
                if (
                    (
                        $compareStmt instanceof Node\Stmt\Class_
                        || $compareStmt instanceof Node\Stmt\Interface_
                        || $compareStmt instanceof Node\Stmt\Trait_
                    )
                    && $compareStmt?->name?->name !== null
                    && strtolower($compareStmt->name->name ?? '') === strtolower($aliasUseLastName)
                ) {
                    continue 2;
                }

                if ($compareStmt === $stmt) {
                    continue;
                }

                if (! $compareStmt instanceof Use_) {
                    continue;
                }

                if (count($compareStmt->uses) !== 1) {
                    continue;
                }

                if (! isset($compareStmt->uses[0])) {
                    continue;
                }

                $use      = $compareStmt->uses[0]->name->toString();
                $lastName = Strings::after($use, '\\', -1) ?? $use;

                if (strtolower($lastName) === strtolower($aliasName) || strtolower($lastName) === strtolower($aliasUseLastName)) {
                    continue 2;
                }
            }

            $stmt->uses[0]->alias = null;
            $hasChanged           = true;

            $nodeFinder = new NodeFinder();
            $allNodes   = $nodeFinder->findInstanceOf($node, Node::class);

            foreach ($allNodes as $allNode) {
                $this->modifyComments($allNode, $aliasName, $aliasUseLastName);
                $this->modifyNodeClassName($allNode, 'name', $aliasName, $aliasUseLastName);
                $this->modifyNodeClassName($allNode, 'class', $aliasName, $aliasUseLastName);
                $this->modifyNodeClassName($allNode, 'extends', $aliasName, $aliasUseLastName);
                $this->modifyNodeClassName($allNode, 'type', $aliasName, $aliasUseLastName);
                $this->modifyNodeClassName($allNode, 'returnType', $aliasName, $aliasUseLastName);
                $this->modifyNodes($allNode, 'implements', $aliasName, $aliasUseLastName);
                $this->modifyNodes($allNode, 'extends', $aliasName, $aliasUseLastName);
                $this->modifyNodes($allNode, 'traits', $aliasName, $aliasUseLastName);
            }

            // if ($aliasName === 'EnumTrait') {
            //     file_put_contents(__DIR__ . 'traituseexample.json', json_encode($node));
            // }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function modifyComments(Node $node, string $alias, string $className): void
    {
        // Docblocks are stored in the 'comments' attribute
        $comments = $node->getAttribute('comments');

        if (! empty($comments)) {
            $newComments = [];

            foreach ($comments as $comment) {
                if ($comment instanceof Doc && preg_match("/(\W)$alias(\W)/", $comment->getText()) === 1) {
                    $newComments[] = new Doc(
                        text: preg_replace("/(\W)$alias(\W)/", "$1$className$2", $comment->getText()),
                    );

                    continue;
                }
                $newComments[] = $comment;
            }

            // Set the filtered comments back to the node
            $node->setAttribute('comments', $newComments);
        }
    }

    private function modifyNodeClassName(Node $node, string $property, string $alias, string $className): void
    {
        $NameNode = $node->$property ?? null;

        if ($NameNode instanceof FullyQualified && $NameNode->getAttribute('originalName')?->name === $alias) {
            $newNameNode = new FullyQualified($NameNode->name);
            $newNameNode->setAttribute('originalName', $className);

            $node->$property = $newNameNode;
        }
    }

    private function modifyNodes(Node $node, string $property, string $alias, string $className): void
    {
        $nameNodes = $node->$property ?? null;

        if (! is_array($nameNodes)) {
            return;
        }

        $newNameNodes = [];

        foreach ($nameNodes as $nameNode) {
            if ($nameNode instanceof FullyQualified && $nameNode->getAttribute('originalName')?->name === $alias) {
                $newClass = new FullyQualified($nameNode->name);
                $newClass->setAttribute('originalName', $className);

                $newNameNodes[] = $newClass;

                continue;
            }

            $newNameNodes[] = $nameNode;
        }

        $node->$property = $newNameNodes;
    }
}
