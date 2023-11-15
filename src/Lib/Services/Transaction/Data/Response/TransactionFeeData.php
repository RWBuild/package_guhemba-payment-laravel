<?php

namespace RWBuild\Guhemba\Lib\Services\Transaction\Data\Response;

use RWBuild\Guhemba\Lib\CustomData\ResponseBaseData;

/**
 * @property numeric id
 * @property numeric agent_fee_amount
 * @property numeric sales_fee_amount
 * @property numeric agency_fee_amount
 * @property numeric transfer_fee_amount
 * @property numeric discount_amount
 * @property numeric redeem_discount_amount
 * @property numeric coupon_discount_amount
 * @property numeric point_discount_amount
 * @property numeric refund_fee_amount
 */
class TransactionFeeData  extends ResponseBaseData
{
  protected function expectedProperties(): array
  {
    return [
      "id?",
      "agent_fee_amount?",
      "sales_fee_amount?",
      "agency_fee_amount?",
      "transfer_fee_amount?",
      "discount_amount?",
      "redeem_discount_amount?",
      "coupon_discount_amount?",
      "point_discount_amount?",
      "refund_fee_amount?",
    ];
  }
}
