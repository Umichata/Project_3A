<?php
    //переменные для размеров матрицы и сама матрица
    $matrix_size=3;
    $matrix[$matrix_size][$matrix_size];
    $matrix_result[$matrix_size][$matrix_size];
    $matrix_positive=0;
    $matrix_negative=0;
    //Матрица с дописанными элементами для правила Саррюса
    $sarius_size=$matrix_size*2-1;
    $sarius_matrix[$matrix_size][$sarius_size];
    $exit=false;
    ////////////////////////////////////////////////////////////////////
    //Можно убрать код с 12 по 26 строку, просто забиваю рандомную матрицу для проверки    -164-(-57
    echo 'Исходная Матрица<br/>';
    
    for ($i=0;$i<$matrix_size;$i++)
    {
        for ($j=0;$j<$matrix_size;$j++)
        {
            $matrix[$i][$j]=rand(-10,10);
            printf("%d\t",$matrix[$i][$j]);
        }
        echo '<br/>';
    }
    unset($i); unset($j);
    ////////////////////////////////////////////////////////////////////   
    //Это определитель матрицы 
    //Правило Саррюса
    
    //Создаем дописанную матрицу
    echo 'Матрица Саррюса<br/>';
    for($i=0;$i<$matrix_size;$i++)
    {
        for ($j=0;$j<$sarius_size;$j++)
        {
            if ($j>=$matrix_size)
            {
                $sarius_matrix[$i][$j]=$matrix[$i][$j-$matrix_size];
                printf("%d\t",$sarius_matrix[$i][$j]);
            }else 
                {
                    $sarius_matrix[$i][$j]=$matrix[$i][$j];
                    printf("%d\t",$sarius_matrix[$i][$j]);
                }
        }
        echo '<br/>';
    }
    unset($i); unset($j);
    //Положительное направление
    for ($i=0;$i<$matrix_size;$i++)
    {
        $line_count=1;
        $line_number=$i;
        for ($j=0;$j<$matrix_size;$j++)
        {
            $line_count*=$sarius_matrix[$j][$line_number];
            $line_number++;
        }
        printf("%d\t",$line_count);
        $matrix_positive+=$line_count;
    }
    printf("Положительное направление%d\t",$matrix_positive);
    unset($i); unset($j); unset($line_count); unset($line_number);
    //Отрицательное направление
    for ($i=0;$i<$matrix_size;$i++)
    {
        $line_count=1;
        $line_number=$sarius_size-1-$i;
        for ($j=0;$j<$matrix_size;$j++)
        {
            $line_count*=$sarius_matrix[$j][$line_number];
            $line_number--;
        }
        printf("%d\t",$line_count);
        $matrix_negative+=$line_count;
    }
    printf("Отрицательное направление%d\t",$matrix_negative);
    //Определитель
    $determinant=$matrix_positive-$matrix_negative;
    unset($i); unset($j); unset($line_count); unset($line_number);
    echo '<br/>Определитель<br/>';
    printf("%d\t",$determinant);
?>    