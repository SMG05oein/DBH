<?php
include "../config/connect.php";

/*
 * ***********************
 * 이 파일은 유용한 함수들을 정의한 클래스 파일입니다.
 * 이 파일에 대한 수정을 새로운 함수 정의에 대해서는 자유롭지만
 * 수정에 대해서는 자기가 만든 함수가 아니면 금지합니다.
 * ***********************
 * */

class loader
{

    /*
     * INSERT 함수
     * */
    function INSERT()
    {
        $sql = "";
        if ($mysqli->query($sql) === TRUE) {
            echo "새로운 레코드가 성공적으로 생성되었습니다.";
        } else {
            echo "오류: " . $sql . "<br>" . $mysqli->error;
        }
    }



}