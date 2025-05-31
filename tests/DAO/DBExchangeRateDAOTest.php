<?php

namespace DAO;

use App\DAO\ExchangeRate\DBExchangeRateDAO;
use App\DAO\ExchangeRate\Exception\ExchangeRateNotFoundException;
use App\Model\ExchangeRate;
use Mockery;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;


class DBExchangeRateDAOTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testFindAllExchangeRates(): void
    {
        $data = [
            [
                'id' => 1,
                'base_currency_id' => 1,
                'base_currency_code' => 'USD',
                'base_currency_fullname' => 'US Dollar',
                'base_currency_sign' => '$',
                'target_currency_id' => 2,
                'target_currency_code' => 'EUR',
                'target_currency_fullname' => 'Euro',
                'target_currency_sign' => '€',
                'rate' => 0.92,
            ],
        ];

        $pdo = Mockery::mock(PDO::class);
        $stmt = Mockery::mock(PDOStatement::class);

        $pdo->shouldReceive('prepare')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn($stmt);

        $stmt->shouldReceive('execute')->once();
        $stmt->shouldReceive('fetchAll')
            ->once()
            ->with(PDO::FETCH_ASSOC)
            ->andReturn($data);

        $repository = new DBExchangeRateDAO($pdo);
        $result = $repository->findAll();

        $this->assertCount(1, $result);
        $this->assertEquals('USD', $result[0]['base_currency_code']);
        $this->assertEquals('EUR', $result[0]['target_currency_code']);
    }

    public function testFindOneExchangeRateData(): void
    {
        $baseId = 1;
        $targetId = 2;

        $expected = [
            'id' => 1,
            'base_currency_id' => 1,
            'base_currency_code' => 'USD',
            'base_currency_fullname' => 'US Dollar',
            'base_currency_sign' => '$',
            'target_currency_id' => 2,
            'target_currency_code' => 'EUR',
            'target_currency_fullname' => 'Euro',
            'target_currency_sign' => '€',
            'rate' => 0.92,
        ];

        $pdo = Mockery::mock(PDO::class);
        $stmt = Mockery::mock(PDOStatement::class);

        $pdo->shouldReceive('prepare')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn($stmt);

        $stmt->shouldReceive('execute')
            ->once()
            ->with(['base_id' => $baseId, 'target_id' => $targetId]);

        $stmt->shouldReceive('fetch')
            ->once()
            ->with(PDO::FETCH_ASSOC)
            ->andReturn($expected);

        $repository = new DBExchangeRateDAO($pdo);
        $result = $repository->findOne($baseId, $targetId);

        $this->assertSame($expected, $result);
    }

    public function testFindOneThrowsExceptionIfNotFound(): void
    {
        $this->expectException(ExchangeRateNotFoundException::class);
        $this->expectExceptionMessage('Обменный курс с base_id: 1 и target_id: 2 не найден');

        $pdo = Mockery::mock(PDO::class);
        $stmt = Mockery::mock(PDOStatement::class);

        $pdo->shouldReceive('prepare')->andReturn($stmt);
        $stmt->shouldReceive('execute')->with(['base_id' => 1, 'target_id' => 2]);
        $stmt->shouldReceive('fetch')->with(PDO::FETCH_ASSOC)->andReturn(false);

        $repository = new DBExchangeRateDAO($pdo);
        $repository->findOne(1, 2);
    }
}