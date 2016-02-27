<?php
    //переменные для размеров матриц и сами матрицы
    $factor=4;
    $matrix_first_rows=2;
    $matrix_first_columns=3;
    $matrix_second_rows=3;
    $matrix_second_columns=5;
    $matrix_first[$matrix_rows][$matrix_columns];
    $matrix_second[$matrix_rows][$matrix_columns];
    //Размер результирующей матрицы для случая умножения матрицы на матрицу X(2,3)*Y(3,5)=T(2,5)
    $matrix_result_mult[$matrix_first_rows][$matrix_second_columns];
    $matrix_result_factor[$matrix_first_rows][$matrix_first_columns];
    
    ////////////////////////////////////////////////////////////////////
    //Можно убрать код с 14 по 41 строку, просто забиваю рандомные матрицы для проверки    
    echo 'Первая матрица<br/>';
    
    for ($i=0;$i<$matrix_first_rows;$i++)
    {
        for ($j=0;$j<$matrix_first_columns;$j++)
        {
            $matrix_first[$i][$j]=rand(-10,10);
            printf("%d\t",$matrix_first[$i][$j]);
        }
        echo '<br/>';
    }
    unset($i); unset($j);
    ////////////////////////////////////////////////////////////////////    
    echo '<br/>Вторая матрица<br/>';
    
    for ($i=0;$i<$matrix_second_rows;$i++)
    {
        for ($j=0;$j<$matrix_second_columns;$j++)
        {
            $matrix_second[$i][$j]=rand(-10,10);
            printf("%d\t",$matrix_second[$i][$j]);
        }
       echo '<br/>';
    }
    unset($i); unset($j);
    ////////////////////////////////////////////////////////////////////   
    //Это умножение матрицы на число    
    echo '<br/>Результат умножения 1-ой матрицы на число factor=4<br/>';
    for ($i=0;$i<$matrix_first_rows;$i++)
    {
        for ($j=0;$j<$matrix_first_columns;$j++)
        {
            $matrix_result_factor[$i][$j]=$matrix_first[$i][$j]*$factor;
            printf("%d\t",$matrix_result_factor[$i][$j]);
        }
        echo '<br/>';
    }
    unset($i); unset($j);
    ////////////////////////////////////////////////////////////////////   
    //Это умножение матрицы на матрицу    
    echo '<br/>Результат умножения матрицы на матрицу<br/>';
    for ($i=0;$i<$matrix_first_rows;$i++)
    {
        for ($j=0;$j<$matrix_second_columns;$j++)
            {
                $matrix_result_mult[$i][$j]=0;
                for ($k=0;$k<$matrix_second_rows;$k++)
                {
                    $matrix_result_mult[$i][$j]+=$matrix_first[$i][$k]*$matrix_second[$k][$j];
                }
                printf("%d\t",$matrix_result_mult[$i][$j]);
            }
        echo '<br/>';
    }
    unset($i); unset($j); unset($k);
?>    