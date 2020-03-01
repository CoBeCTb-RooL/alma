<?php
class TangentLeastSquare{
    public $dots;
    public $sigmaAtm;
    public $S;
    public $T;

    public $x;
    public $y;
    public $xy;
    public $x2;

    public $E;

    public function __construct($dotsArr, $sigmaAtm, $S, $T)
    {
//        foreach ($dotsArr as $key=>$val)
//            $dotsArr[$key][0]  = $dotsArr[$key][0]*1000-1000;

        $this->dots = $dotsArr;
        $this->sigmaAtm = $sigmaAtm;
        $this->S = $S;
        $this->T = $T;


        #   бежим по всем
        $this->yMin = $this->yMax = 0;
        foreach ($this->dots as $i=>$val)
        {
            list($x, $y, $isCentral) = $val;


            #   определим наибольшие и наименьшие  значения Y
            $this->yMin = $this->yMin < $y && $this->yMin!=0 ? $this->yMin : $y;
            $this->yMax = $this->yMax > $y ? $this->yMax : $y;

            if($isCentral)
                $centralIndex = $i;
        }
//        vd($dotsArr);

        #   формируем массив ближайших точек для касательной
        $nearestDots = [
            $this->dots[$centralIndex-1],
            $this->dots[$centralIndex],
            $this->dots[$centralIndex+1],
        ];

//        $nearestDots = $this->dots;

        #   ВЫЯСНЯЕМ КАСАТЕЛЬНУЮ - ТОЛЬКО ПО БЛИЖАЙШИМ К ЦЕНТРАЛЬНОЙ!
        foreach ($nearestDots as $i=>$val)
        {
            list($x, $y) = $val;
//            $x*=1000;
//            $x = $i+1;
//            vd($x);

            $this->x[$i] = $x;
            $this->y[$i] = $y;
            $this->xy[$i] = $x * $y;
            $this->x2[$i] = $x * $x;

            $this->E['x'] += $this->x[$i];
            $this->E['y'] += $this->y[$i];
            $this->E['xy'] += $this->xy[$i];
            $this->E['x2'] += $this->x2[$i];


            $this->alpha = ( count($dotsArr) * $this->E['xy'] - $this->E['x'] * $this->E['y'] )   /   ( count($dotsArr)*$this->E['x2'] - $this->E['x'] * $this->E['x'] ) ;
            $this->beta = ( $this->E['y'] - $this->alpha * $this->E['x'] ) / count($dotsArr);

            $this->alpha = round($this->alpha, 4);
            $this->beta = round($this->beta, 4);
            $this->formula = 'f(x) = '.$this->alpha.'*x '.($this->beta >=0 ? '+' : '').' '.$this->beta.'';

            #   угол (в формуле - БЭТА)
            $this->scewAngle = rad2deg(atan($this->alpha));




            #   КРИВИЗНА - лямбда
            $znamenatel = 1 + pow(($this->alpha*$this->dots[$centralIndex][0] + $this->beta), 2);
            $this->lambda =  abs($this->alpha) / pow($znamenatel, 1.5) ;

//            vd($this);
//            vd($this->dots[$centralIndex]);

        }
    }




    function ksi($k)
    {
//        echo 'k = '.$k.'<br>';
//        echo 'S = '.$this->S.'<br>';
//        echo 'k/s = '.$k/$this->S.'<br>';
//        echo 'ln(k/s) = '.log($k/$this->S).'<br>';
//
//        echo 'sqrt(T) = '.sqrt($this->T).'<br>';
//        echo 'sigmaAtm * sqrt(T) = '.($this->sigmaAtm * sqrt($this->T)).'<br>';
//
//        echo '<hr>';


        $ret = round(  log($k/$this->S) / ( $this->sigmaAtm * sqrt($this->T) ) ,  5 );

        return $ret;
    }


    function sigmaCorrect($k)
    {
        $ret = $this->sigmaAtm * (1 + ($this->skewAngle/6 * $this->ksi($k))  +  ($this->lambda/24 * pow($this->ksi($k), 2)  )   );

        return $ret;
    }

}