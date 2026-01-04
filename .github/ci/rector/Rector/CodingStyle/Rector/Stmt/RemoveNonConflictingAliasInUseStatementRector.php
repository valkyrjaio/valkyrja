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
use function str_contains;
use function str_replace;
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
                if ($compareStmt instanceof Node\Stmt\Class_ && strtolower($compareStmt->name->toString()) === strtolower($aliasUseLastName)) {
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
                $this->modifyClassName($allNode, $aliasName, $aliasUseLastName);
            }
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
                if ($comment instanceof Doc && str_contains($comment->getText(), $alias)) {
                    $newComments[] = new Doc(
                        text: str_replace($alias, $className, $comment->getText()),
                    );

                    continue;
                }
                $newComments[] = $comment;
            }

            // Set the filtered comments back to the node
            $node->setAttribute('comments', $newComments);
        }
    }

    private function modifyClassName(Node $node, string $alias, string $className): void
    {
        $class = $node->class ?? null;

        if ($class instanceof FullyQualified && $class->getAttribute('originalName')?->name === $alias) {
            $newClass = new FullyQualified($class->name);
            $newClass->setAttribute('originalName', $className);

            $node->class = $newClass;
        }
    }
}
