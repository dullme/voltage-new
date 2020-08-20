<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Abolished()
 * @method static static SelectTypical()
 * @method static static AddBlock()
 * @method static static Completed()
 */
final class QuotationStatus extends Enum implements LocalizedEnum
{

    const ABOLISHED = 0; //已作废
    const SELECT_TYPICAL = 1; //选择 Typical
    const ADD_BLOCK = 2; //添加 Block
    const COMPLETED = 10;//已完成

}
