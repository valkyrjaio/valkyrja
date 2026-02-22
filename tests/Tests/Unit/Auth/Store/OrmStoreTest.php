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

namespace Valkyrja\Tests\Unit\Auth\Store;

use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Auth\Data\Retrieval\RetrievalById;
use Valkyrja\Auth\Data\Retrieval\RetrievalByIdAndUsername;
use Valkyrja\Auth\Data\Retrieval\RetrievalByUsername;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Auth\Store\OrmStore;
use Valkyrja\Auth\Throwable\Exception\InvalidRetrievableUserException;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Repository\Contract\RepositoryContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use const PASSWORD_DEFAULT;

/**
 * Test the OrmStore class.
 */
final class OrmStoreTest extends TestCase
{
    protected const string USER_ID   = 'user-123';
    protected const string USERNAME  = 'testuser';
    protected const string PASSWORD  = 'SecureP@ssw0rd!';

    protected ManagerContract&MockObject $orm;
    protected RepositoryContract&MockObject $repository;
    protected OrmStore $store;
    protected User $user;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(RepositoryContract::class);
        $this->orm        = $this->createMock(ManagerContract::class);

        $this->store = new OrmStore($this->orm);

        $this->user           = new User();
        $this->user->id       = self::USER_ID;
        $this->user->username = self::USERNAME;
        $this->user->password = password_hash(self::PASSWORD, PASSWORD_DEFAULT);
    }

    public function testRetrieveByIdCallsRepositoryFindBy(): void
    {
        $retrieval = new RetrievalById(self::USER_ID);

        $this->orm->expects($this->exactly(2))
            ->method('createRepository')
            ->with(User::class)
            ->willReturn($this->repository);

        $this->repository->expects($this->exactly(2))
            ->method('findBy')
            ->willReturn($this->user);

        self::assertTrue($this->store->hasRetrievable($retrieval, User::class));

        $result = $this->store->retrieve($retrieval, User::class);

        self::assertSame($this->user, $result);
    }

    public function testRetrieveByUsernameCallsRepositoryFindBy(): void
    {
        $retrieval = new RetrievalByUsername(self::USERNAME);

        $this->orm->expects($this->exactly(2))
            ->method('createRepository')
            ->with(User::class)
            ->willReturn($this->repository);

        $this->repository->expects($this->exactly(2))
            ->method('findBy')
            ->willReturn($this->user);

        self::assertTrue($this->store->hasRetrievable($retrieval, User::class));

        $result = $this->store->retrieve($retrieval, User::class);

        self::assertSame($this->user, $result);
    }

    public function testRetrieveByIdAndUsernameCallsRepositoryFindByWithMultipleWhere(): void
    {
        $retrieval = new RetrievalByIdAndUsername(self::USER_ID, self::USERNAME);

        $this->orm->expects($this->exactly(2))
            ->method('createRepository')
            ->with(User::class)
            ->willReturn($this->repository);

        $this->repository->expects($this->exactly(2))
            ->method('findBy')
            ->willReturn($this->user);

        self::assertTrue($this->store->hasRetrievable($retrieval, User::class));

        $result = $this->store->retrieve($retrieval, User::class);

        self::assertSame($this->user, $result);
    }

    public function testRetrieveThrowsWhenUserNotFound(): void
    {
        $this->expectException(InvalidRetrievableUserException::class);
        $this->expectExceptionMessage('A user could not be retrieved with the given criteria');

        $retrieval = new RetrievalById('non-existent-id');

        $this->orm->expects($this->exactly(2))
            ->method('createRepository')
            ->with(User::class)
            ->willReturn($this->repository);

        $this->repository->expects($this->exactly(2))
            ->method('findBy')
            ->willReturn(null);

        self::assertFalse($this->store->hasRetrievable($retrieval, User::class));

        $this->store->retrieve($retrieval, User::class);
    }

    public function testCreateCallsRepositoryCreate(): void
    {
        $this->orm->expects($this->once())
            ->method('createRepository')
            ->with(User::class)
            ->willReturn($this->repository);

        $this->repository->expects($this->once())
            ->method('create')
            ->with($this->user);

        $this->store->create($this->user);
    }

    public function testUpdateCallsRepositoryUpdate(): void
    {
        $this->orm->expects($this->once())
            ->method('createRepository')
            ->with(User::class)
            ->willReturn($this->repository);

        $this->repository->expects($this->once())
            ->method('update')
            ->with($this->user);

        $this->store->update($this->user);
    }
}
