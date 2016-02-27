<?php
    //переменные для размеров матриц и сами матрицы
    $matrix_rows=3;
    $matrix_columns=3;
    $matrix_first[$matrix_rows][$matrix_columns];
    $matrix_second[$matrix_rows][$matrix_columns];
    $matrix_result_sum[$matrix_rows][$matrix_columns];
    $matrix_result_sub[$matrix_rows][$matrix_columns];
    
    ////////////////////////////////////////////////////////////////////
    //Можно убрать код с 10 по 36 строку, просто забиваю рандомные матрицы для проверки    
    echo 'Первая матрица<br/>';
    
    for ($i=0;$i<$matrix_rows;$i++)
    {
        for ($j=0;$j<$matrix_columns;$j++)
        {
            $matrix_first[$i][$j]=rand(-10,10);
            printf("%d\t",$matrix_first[$i][$j]);
        }
        echo '<br/>';
    }
    unset($i); unset($j);
    ////////////////////////////////////////////////////////////////////    
    echo '<br/>Вторая матрица<br/>';
    
    for ($i=0;$i<$matrix_rows;$i++)
    {
        for ($j=0;$j<$matrix_columns;$j++)
        {
            $matrix_second[$i][$j]=rand(-10,10);
            printf("%d\t",$matrix_second[$i][$j]);
        }
       echo '<br/>';
    }
    unset($i); unset($j);
    ////////////////////////////////////////////////////////////////////   
    //Это сложение матриц    
    echo '<br/>Результат сложения<br/>';
    for ($i=0;$i<$matrix_rows;$i++)
    {
        for ($j=0;$j<$matrix_columns;$j++)
        {
            $matrix_result_sum[$i][$j]=$matrix_first[$i][$j]+$matrix_second[$i][$j];
            printf("%d\t",$matrix_result_sum[$i][$j]);
        }
        echo '<br/>';
    }
    unset($i); unset($j);
    ////////////////////////////////////////////////////////////////////   
    //Это вычитание матриц    
    echo '<br/>Результат вычитания<br/>';
    for ($i=0;$i<$matrix_rows;$i++)
    {
        for ($j=0;$j<$matrix_columns;$j++)
        {
            $matrix_result_sub[$i][$j]=$matrix_first[$i][$j]-$matrix_second[$i][$j];
            printf("%d\t",$matrix_result_sub[$i][$j]);
        }
        echo '<br/>';
    }
    unset($i); unset($j);
?>    