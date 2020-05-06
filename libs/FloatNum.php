<?php namespace Libs;


class FloatNum
{

    public static function reduce($original, $reduce)
    {
        $original = $original * 100;
        $reduce   = $reduce * 100;
        $final    = $original - $reduce;
        $final    = round($final);

        return $final / 100;
    }

    public static function add($original, $reduce)
    {
        $original = $original * 100;
        $reduce   = $reduce * 100;
        $final    = $original + $reduce;
        $final    = round($final);

        return $final / 100;
    }

    public static function multiply($original, $reduce)
    {
        $original = $original * 100;
        $reduce   = $reduce * 100;
        $final    = $original * $reduce;
        $final    = round($final);

        return $final / 100;
    }

    public static function remove($original, $reduce)
    {
        $original = $original * 100;
        $reduce   = $reduce * 100;
        $final    = $original / $reduce;
        $final    = round($final);

        return $final / 100;
    }
}
