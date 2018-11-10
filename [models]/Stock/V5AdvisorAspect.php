<?php


class V5AdvisorAspect{

    public $question;
    public $advise;

//    static $questionsDict = [
//        0=>'Открытие ниже MAX ? ',
//        1=>'Открытие ниже НИЖНЕГО ЗЕЛЁНОГО? ',
//        2=>'Открытие ниже ЧЁРНОГО? ',
//        3=>'ВЕРХНИЙ КРАСНЫЙ ниже МАХ? ',
//    ];


    function __construct($question, $answer)
    {
        $this->question = $question;
        $this->result = $answer;
    }


//    function aspect($num)
//    {
//        return self::$questionsDict[$num];
//    }


}