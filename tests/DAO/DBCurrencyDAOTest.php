<?php

namespace DAO;

use App\DAO\Currency\DBCurrencyDAO;
use App\DAO\Currency\Exception\CurrencyNotFoundException;
use App\Model\Currency;
use Mockery;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;


class DBCurrencyDAOTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testFindAllCurrencies(): void
    {
        $data = [
            ['id' => 1, 'code' => 'USD', 'fullname' => 'US Dollar', 'sign' => '$'],
            ['id' => 2, 'code' => 'EUR', 'fullname' => 'Euro', 'sign' => '€'],
        ];

        $pdo = Mockery::mock(PDO::class);
        $stmt = Mockery::mock(PDOStatement::class);

        $pdo->shouldReceive('prepare')
            ->once()
            ->with("SELECT * FROM currencies;")
            ->andReturn($stmt);

        $stmt->shouldReceive('execute')->once();
        $stmt->shouldReceive('fetchAll')
            ->once()
            ->with(PDO::FETCH_ASSOC)
            ->andReturn($data);

        $repository = new DBCurrencyDAO($pdo);
        $result = $repository->findAll();

        $this->assertCount(2, $result);
        $this->assertInstanceOf(Currency::class, $result[0]);
        $this->assertEquals('USD', $result[0]->getCode());
        $this->assertEquals('$', $result[0]->getSign());
    }

    public function testFindCurrency(): void
    {
        $code = 'USD';
        $data = ['id' => 1, 'code' => 'USD', 'fullname' => 'US Dollar', 'sign' => '$'];

        $pdo = Mockery::mock(PDO::class);
        $stmt = Mockery::mock(PDOStatement::class);

        $pdo->shouldReceive('prepare')
            ->once()
            ->with("SELECT * FROM currencies WHERE code ILIKE :code;")
            ->andReturn($stmt);

        $stmt->shouldReceive('execute')
            ->once()
            ->with(['code' => $code]);

        $stmt->shouldReceive('fetch')
            ->once()
            ->with(PDO::FETCH_ASSOC)
            ->andReturn($data);

        $repository = new DBCurrencyDAO($pdo);
        $currency = $repository->findOne($code);

        $this->assertInstanceOf(Currency::class, $currency);
        $this->assertEquals('USD', $currency->getCode());
        $this->assertEquals('US Dollar', $currency->getFullname());
    }

    public function testFindNotFoundCurrency(): void
    {
        $this->expectException(CurrencyNotFoundException::class);
        $this->expectExceptionMessage('Валюта с кодом XXX не найдена');

        $code = 'XXX';

        $pdo = Mockery::mock(PDO::class);
        $stmt = Mockery::mock(PDOStatement::class);

        $pdo->shouldReceive('prepare')
            ->once()
            ->with("SELECT * FROM currencies WHERE code ILIKE :code;")
            ->andReturn($stmt);

        $stmt->shouldReceive('execute')
            ->once()
            ->with(['code' => $code]);

        $stmt->shouldReceive('fetch')
            ->once()
            ->with(PDO::FETCH_ASSOC)
            ->andReturn(false);

        $repository = new DBCurrencyDAO($pdo);
        $repository->findOne($code);
    }
}