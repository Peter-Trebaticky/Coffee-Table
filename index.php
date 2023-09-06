<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .square {
            width: 30px;
            height: 30px;
            border: 1px solid black;
            display: inline-block;
            text-align: center;
            line-height: 30px;
        }
        .container {
            border: 1px solid black;
            display: inline-block;
            padding: 5px;
            background-color: #9C9C9C;
        }
        .coffee {
            background-color: #8B4513;
        }
    </style>
</head>
<body>
    <h1>Coffee Table</h1>
    <div class="container">
        <?php
        class CoffeeTable {
            protected array $table;

            public function __construct()
            {
                for ($row = 0; $row < 20; $row++) {
                    for ($col = 0; $col < 20; $col++) {
                        $this->table[$row][$col] = rand(1, 100) <= 20 ? 1 : 0;
                        $number = $this->table[$row][$col] ? 1 : 0;
                        echo '<div class="square ' . ($this->table[$row][$col] ? 'coffee' : '') . '">';
                        echo $number;
                        echo '</div>';
                    }
                    echo '<br>';
                }
            }

            protected function dfs(&$visited, $row, $col, &$group, &$currentGroup)
            {
                $rows = count($this->table);
                $cols = count($this->table[0]);

                if ($row < 0 || $row >= $rows || $col < 0 || $col >= $cols || $visited[$row][$col] || $this->table[$row][$col] === 0) {
                    return;
                }

                $visited[$row][$col] = true;

                $group[] = "$row,$col";

                $this->dfs($visited, $row - 1, $col - 1, $group, $currentGroup);
                $this->dfs($visited, $row - 1, $col, $group, $currentGroup);
                $this->dfs($visited, $row - 1, $col + 1, $group, $currentGroup);
                $this->dfs($visited, $row, $col - 1, $group, $currentGroup);
                $this->dfs($visited, $row, $col + 1, $group, $currentGroup);
                $this->dfs($visited, $row + 1, $col - 1, $group, $currentGroup);
                $this->dfs($visited, $row + 1, $col, $group, $currentGroup);
                $this->dfs($visited, $row + 1, $col + 1, $group, $currentGroup);
            }
        }

        class StainTable extends CoffeeTable {
            private array $stains = [];

            public function __construct()
            {
                parent::__construct();
                $this->stains = $this->findStains();
            }

            public function findStains()
            {
                $rows = count($this->table);
                $cols = count($this->table[0]);
                $visited = array_fill(0, $rows, array_fill(0, $cols, false));
                $currentGroup = 0;
                $stains = array();

                for ($row = 0; $row < $rows; $row++) {
                    for ($col = 0; $col < $cols; $col++) {
                        if (!$visited[$row][$col] && $this->table[$row][$col] === 1) {
                            $currentGroup++;
                            $group = array();
                            $this->dfs($visited, $row, $col, $group, $currentGroup);
                            $stains[$currentGroup] = $group;
                        }
                    }
                }

                return $stains;
            }

            public function findLargestStain()
            {
                $largestStainSize = 0;
                $largestStain = array();

                foreach ($this->stains as $group => $cells) {
                    if (count($cells) > $largestStainSize) {
                        $largestStainSize = count($cells);
                        $largestStain = $cells;
                    }
                }

                return array('size' => $largestStainSize, 'cells' => $largestStain);
            }

            public function showLargestStain()
            {
                $totalStains = count($this->stains);

                $largestStainData = $this->findLargestStain();
                $largestStainSize = $largestStainData['size'];
                $largestStainCells = $largestStainData['cells'];

                $largestStainIndex = null;
                foreach ($this->stains as $index => $cells) {
                    if ($cells === $largestStainCells) {
                        $largestStainIndex = $index;
                        break;
                    }
                }

                echo '<br>';
                echo 'Celkový počet škvŕn je: ' . $totalStains . '<br>';
                echo 'Najväčšia škvrna je číslo ' . $largestStainIndex . ' a má veľkosť ' . $largestStainSize . ' políčok.','<br>';
                echo '<br>';

                for ($row = 0; $row < 20; $row++) {
                    for ($col = 0; $col < 20; $col++) {
                        $number = '';
                        if ($this->table[$row][$col] === 1) {
                            $groupNumber = null;
                            foreach ($this->stains as $group => $cells) {
                                if (in_array("$row,$col", $cells)) {
                                    $groupNumber = $group;
                                    break;
                                }
                            }
                            $number = $groupNumber !== null ? $groupNumber : '';
                        } else {
                            $number = '0';
                        }

                        $classes = 'square ' . ($this->table[$row][$col] === 1 ? 'coffee' : '');
                        echo '<div class="' . $classes . '">';
                        echo $number;
                        echo '</div>';
                    }
                    echo '<br>';
                }
            }
        }

        $stainTable = new StainTable();
        $stainTable->showLargestStain();
        ?>
    </div>
</body>
</html>
