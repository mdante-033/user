<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class AuthTest extends TestCase
{
    /**
     * Test that a correctly hashed password verifies successfully.
     */
    public function testCorrectPasswordVerifies(): void
    {
        $plainPassword = 'Admin123!';
        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);

        $this->assertTrue(password_verify($plainPassword, $hash));
    }

    /**
     * Test that an incorrect password fails verification.
     */
    public function testIncorrectPasswordFailsVerification(): void
    {
        $plainPassword = 'Admin123!';
        $wrongPassword = 'Admin123!!@';
        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);

        $this->assertFalse(password_verify($wrongPassword, $hash));
    }

    /**
     * Test that a completely different password fails verification.
     */
    public function testWrongPasswordFails(): void
    {
        $hash = password_hash('Admin123!', PASSWORD_DEFAULT);

        $this->assertFalse(password_verify('wrong-password', $hash));
    }

    /**
     * Test that password_hash produces a valid bcrypt hash (starts with $2y$).
     */
    public function testHashFormatIsValid(): void
    {
        $hash = password_hash('AnyPassword123!', PASSWORD_DEFAULT);

        $this->assertStringStartsWith('$2y$', $hash);
        $this->assertGreaterThan(50, strlen($hash));
    }

    /**
     * Test the strong password logic used in AuthController.
     * A strong password must be: ≥8 chars, uppercase, lowercase, digit, special char.
     *
     * @dataProvider passwordStrengthProvider
     */
    public function testPasswordStrength(string $password, bool $expectedStrong): void
    {
        $isStrong = $this->validateStrongPassword($password);

        $this->assertSame($expectedStrong, $isStrong);
    }

    /**
     * Data provider for password strength tests.
     *
     * @return array<string, array{string, bool}>
     */
    public static function passwordStrengthProvider(): array
    {
        return [
            'strong password'              => ['Admin123!', true],
            'missing uppercase'            => ['admin123!', false],
            'missing lowercase'            => ['ADMIN123!', false],
            'missing digit'                => ['AdminPass!', false],
            'missing special char'         => ['Admin1234', false],
            'too short'                    => ['Ad1!', false],
            'exactly 8 chars strong'       => ['Adm1n!!@', true],
            'empty password'               => ['', false],
        ];
    }

    /**
     * Mirror of AuthController::strongPassword() for testing.
     */
    private function validateStrongPassword(string $password): bool
    {
        return strlen($password) >= 8
            && preg_match('/[A-Z]/', $password) === 1
            && preg_match('/[a-z]/', $password) === 1
            && preg_match('/[0-9]/', $password) === 1
            && preg_match('/[^A-Za-z0-9]/', $password) === 1;
    }
}