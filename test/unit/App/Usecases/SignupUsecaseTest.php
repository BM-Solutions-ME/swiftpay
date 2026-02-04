<?php

declare(strict_types=1);

namespace unit\App\Usecases;

use PHPUnit\Framework\TestCase;
use Source\App\Usecases\Signup\SignupInputData;
use Source\App\Usecases\Signup\SignupOutputData;
use Source\App\Usecases\Signup\SignupUsecase;
use Source\Domain\Entities\User;
use Source\Domain\Enum\UserStatusEnum;
use Source\Domain\Repositories\SignupRepositoryInterface;

final class SignupUsecaseTest extends TestCase
{
    public function testHandleCreatesUser()
    {
        $input = new SignupInputData(
            firstName: 'João',
            lastName: 'Silva',
            type: 'F',
            document: '085.880.850-11',
            email: 'joao@example.com',
            password: 'hashed123'
        );

        $repositoryMock = $this->createMock(SignupRepositoryInterface::class);

        $willReturnExample = new User;
        $willReturnExample->hydrate([
            'id' => 1,
            'first_name' => 'João',
            'last_name' => 'Silva',
            'type' => 'F',
            'document' => '085.880.850-11',
            'email' => 'joao@example.com',
            'level' => 1,
            'status' => UserStatusEnum::Registered->value,
            'created_at' => '2025-08-14 15:30:00'
        ]);
        $repositoryMock->expects($this->once())
            ->method('register')
            ->with($this->isInstanceOf(User::class))
            ->willReturn($willReturnExample);

        $signup = new SignupUsecase($repositoryMock);
        $output = $signup->handle($input);

        $this->assertInstanceOf(SignupOutputData::class, $output);
        $this->assertEquals(1, $output->getId());
        $this->assertEquals('João', $output->getFirstName());
        $this->assertEquals('Silva', $output->getLastName());
        $this->assertEquals('F', $output->getType());
        $this->assertEquals('085.880.850-11', $output->getDocument());
        $this->assertEquals('joao@example.com', $output->getEmail());
        $this->assertEquals(1, $output->getLevel());
        $this->assertEquals('registered', $output->getStatus());
        $this->assertEquals('2025-08-14 15:30:00', $output->getCreatedAt());
    }
}