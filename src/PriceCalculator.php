<?php

namespace MarcelStrahl\PriceCalculator;

use MarcelStrahl\PriceCalculator\Helpers\Entity\VatInterface;

/**
 * Class PriceCalculator
 * @author Marcel Strahl <info@marcel-strahl.de>
 * @package Src
 */
class PriceCalculator implements PriceCalculatorInterface
{
    /**
     * @var VatInterface
     */
    private $vat;

    /**
     * @param $vat
     */
    public function __construct(VatInterface $vat)
    {
        $this->vat = $vat;
    }

    /**
     * Add an price to total
     *
     * @param float $total
     * @param float $price
     * @return float
     */
    public function addPrice(float $total, float $price): float
    {
        return (float)bcadd($total, $price);
    }

    /**
     * Sub an price from total
     *
     * @param float $total
     * @param float $price
     * @return float
     */
    public function subPrice(float $total, float $price): float
    {
        $total = (float)bcsub($total, $price);
        return ($total < 0) ? 0 : $total;
    }

    /**
     * Mul an price with amount
     *
     * @param float $amount
     * @param float $price
     * @return float
     */
    public function mulPrice(float $amount, float $price): float
    {
        return (float)bcmul($price, $amount);
    }

    /**
     * Calculates the gross price
     *
     * @param float $netPrice
     * @return float
     */
    public function calculatePriceWithSalesTax(float $netPrice): float
    {
        return (float)round((float)bcmul($netPrice, $this->vat->getVatToCalculate(), 2));
    }

    /**
     * Calculates the value added tax from the current total price
     *
     * @param float $total
     * @return float
     */
    public function calculateSalesTaxFromTotalPrice(float $total) : float
    {
        return (float)bcsub($total, round((float)bcdiv($total, $this->vat->getVatToCalculate(), 2)));
    }

    /**
     * Calculates the net price from the gross price
     *
     * @param float $total
     * @return float
     */
    public function calculateNetPriceFromGrossPrice(float $total) : float
    {
        $vatPrice = $this->calculateSalesTaxFromTotalPrice($total);
        return $this->subPrice($total, $vatPrice);
    }

    /**
     * @param float $vat
     * @return void
     */
    public function setVat(float $vat): void
    {
        $this->vat->setVat($vat);
    }
}
