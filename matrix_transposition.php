<?php
    //переменные для размеров матрицы и сама матрица
    $matrix_rows=3;
    $matrix_columns=3;
    $matrix[$matrix_rows][$matrix_columns];
    $matrix_result[$matrix_rows][$matrix_columns];
    
    ////////////////////////////////////////////////////////////////////
    //Можно убрать код с 8 по 22 строку, просто забиваю рандомные матрицы для проверки    
    echo 'Исходная матрица<br/>';
    
    for ($i=0;$i<$matrix_rows;$i++)
    {
        for ($j=0;$j<$matrix_columns;$j++)
        {
            $matrix[$i][$j]=rand(-10,10);
            printf("%d\t",$matrix[$i][$j]);
        }
        echo '<br/>';
    }
    unset($i); unset($j);
    ////////////////////////////////////////////////////////////////////   
    //Это транспонирование матрицы     
    echo '<br/>Транспонированная матрица<br/>';
    for ($i=0;$i<$matrix_rows;$i++)
    {
        for ($j=0;$j<$matrix_columns;$j++)
        {
            $matrix_result[$i][$j]=$matrix[$j][$i];
            printf("%d\t",$matrix_result[$i][$j]);
        }
        echo '<br/>';
    }
    unset($i); unset($j);
?>    