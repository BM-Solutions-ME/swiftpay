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
    public function testHandleCreatesUserAndFormatsDate()
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

        $repositoryMock->expects($this->once())
            ->method('register')
            ->with($this->callback(function (User $user) use ($input) {
                return $user->getType() === $input->getType()
                    && $user->getFirstName() === $input->getFirstName()
                    && $user->getLastName() === $input->getLastName()
                    && $user->getDocument() === $input->getDocument()
                    && $user->getEmail() === $input->getEmail()
                    && $user->getPassword() === $input->getPassword()
                    && $user->getLevel() === 1
                    && $user->getStatus() === UserStatusEnum::Registered;
            }))
            ->willReturn([
                'id' => 1,
                'created_at' => '2025-08-14 15:30:00'
            ]);

        $signup = new SignupUsecase($repositoryMock);
        $output = $signup->handle($input);

        $this->assertInstanceOf(SignupOutputData::class, $output);
        $this->assertEquals('14/08/2025 15h30', $output->getCreatedAt());
    }
}