<?php
class Bacon
{
    private $storage;
    private $tree;
    private $used_persons;
    private $used_series;
    private $used_movies;
    private $used_directed_series;
    private $used_directed_movies;
    private $array = [];
    private $depth;

    public function __construct($storage)
    {
        $this->storage = $storage;
        $this->depth = 0;
        $this->tree = [];
        $this->used_persons = [];
        $this->used_series = [];
        $this->used_movies = [];
        $this->used_directed_series = [];
        $this->used_directed_movies = [];
        $this->array = [];
    }
    public function theMovieDBForm()
    {
        echo '<form method="post">';
        echo '<input type="text" name="actor_name">';
        echo '<input type="submit" name="actor_submit">';
        echo '</form>';
    }
    public function theMovieDB($actor)
    {
        // https://image.tmdb.org/t/p/original/ywH1VvdwqlcnuwUVr0pV0HUZJQA.jpg
        $content = file_get_contents('https://api.themoviedb.org/3/search/person?api_key=906d85a2d561a1998026d96bbee93f3d&query=' . urlencode($actor));
        $content = json_decode($content);
        foreach ($content->results as $cont) {
            // print_r($cont);
            echo '<h2>' . $cont->name . '</h2>';
            echo '<img heigth="200px" width="200px" src="https://image.tmdb.org/t/p/original' . $cont->profile_path . '" alt="' . $cont->name . '">';
            $movies = file_get_contents('https://api.themoviedb.org/3/person/'
            . $cont->id . '/movie_credits?api_key=906d85a2d561a1998026d96bbee93f3d&language=en-US');
            $movies = json_decode($movies);
            echo '<h3>Filme:</h3>';
            echo '<table class="table">';
            echo '<tbody>';
            foreach ($movies->cast as $movie) {
                echo '<tr>';
                echo '<form method="post">';
                echo '<td>' . $movie->title . '</td>';
                echo '<td><input type="submit" name="movie" value="Details">
                <input type="hidden" name="movie_hidden" value="' . $movie->id . '"></td>';
                echo '</form>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            $series = file_get_contents('https://api.themoviedb.org/3/person/'
            . $cont->id . '/tv_credits?api_key=906d85a2d561a1998026d96bbee93f3d&language=en-US');
            $series = json_decode($series);
            echo '<br /><h3>Serien:</h3>';
            echo '<table class="table">';
            echo '<tbody>';
            foreach ($series->cast as $s) {
                echo '<tr>';
                echo '<form method="post">';
                echo '<td>' . $s->name . '</td>';
                echo '<td><input type="submit" name="series" value="Details">
                <input type="hidden" name="series_hidden" value="' . $s->id . '"></td>';
                echo '</form>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            break;
        }
    }


    public function showForm()
    {
        echo '<form method="post">';
        echo '<select name="first">';
        $persons = $this->storage->getPersons();
        foreach ($persons as $person) {
            echo '<option value="' . $person->getID() . '">' . $person->getName() . '</option>';
        }
        echo '</select>';
        echo '<select name="second">';
        foreach ($persons as $person) {
            echo '<option value="' . $person->getID() . '">' . $person->getName() . '</option>';
        }
        echo '</select>';
        echo '<input type="submit" name="submit_bacon">';
        echo '</form>';
    }

    public function createArray($first)
    {
        $this->array[] = $first;
        $this->used_persons[] = $first;
        $persons_of_gen = [];
        $series = $this->storage->getSeriesOfPerson($first);
        if ($series) {
            foreach ($series as $s) {
                if (!in_array($s->getID(), $this->used_series)) {
                    $this->used_series[] = $s->getID();
                    $persons = $this->storage->getActorsOfSeries($s->getID());
                    if ($persons) {
                        foreach ($persons as $person) {
                            if (!in_array($person->getID(), $this->used_persons)) {
                                $this->used_persons[] = $person->getID();
                                $persons_of_gen[] = $person->getID();
                            }
                        }
                    }
                    $directors = $this->storage->getDirectorsOfSeries($s->getID());
                    if ($directors) {
                        foreach ($directors as $director) {
                            if (!in_array($director->getID(), $this->used_persons)) {
                                $this->used_persons[] = $director->getID();
                                $persons_of_gen[] = $director->getID();
                            }
                        }
                    }
                }
            }
        }
        $directed_series = $this->storage->getDirectedSeriesOfPerson($first);
        if ($directed_series) {
            foreach ($directed_series as $directed_s) {
                if (!in_array($directed_s->getID(), $this->used_directed_series)) {
                    $this->used_directed_series[] = $directed_s->getID();
                    $persons = $this->storage->getActorsOfSeries($directed_s->getID());
                    if ($persons) {
                        foreach ($persons as $person) {
                            if (!in_array($person->getID(), $this->used_persons)) {
                                $this->used_persons[] = $person->getID();
                                $persons_of_gen[] = $person->getID();
                            }
                        }
                    }
                    $directors = $this->storage->getDirectorsOfSeries($directed_s->getID());
                    if ($directors) {
                        foreach ($directors as $director) {
                            if (!in_array($director->getID(), $this->used_persons)) {
                                $this->used_persons[] = $director->getID();
                                $persons_of_gen[] = $director->getID();
                            }
                        }
                    }
                }
            }
        }
        $movies = $this->storage->getMoviesOfPerson($first);
        if ($movies) {
            foreach ($movies as $movie) {
                if (!in_array($movie->getID(), $this->used_movies)) {
                    $this->used_movies[] = $movie->getID();
                    $persons = $this->storage->getActorsOfMovie($movie->getID());
                    if ($persons) {
                        foreach ($persons as $person) {
                            if (!in_array($person->getID(), $this->used_persons)) {
                                $this->used_persons[] = $person->getID();
                                $persons_of_gen[] = $person->getID();
                            }
                        }
                    }
                    $directors = $this->storage->getDirectorsOfMovie($movie->getID());
                    if ($directors) {
                        foreach ($directors as $director) {
                            if (!in_array($director->getID(), $this->used_persons)) {
                                $this->used_persons[] = $director->getID();
                                $persons_of_gen[] = $director->getID();
                            }
                        }
                    }
                }
            }
        }
        $directed_movies = $this->storage->getDirectedMoviesOfPerson($first);
        if ($directed_movies) {
            foreach ($directed_movies as $directed_movie) {
                if (!in_array($directed_movie->getID(), $this->used_directed_movies)) {
                    $this->used_directed_movies[] = $directed_movie->getID();
                    $persons = $this->storage->getActorsOfMovie($directed_movie->getID());
                    if ($persons) {
                        foreach ($persons as $person) {
                            if (!in_array($person->getID(), $this->used_persons)) {
                                $this->used_persons[] = $person->getID();
                                $persons_of_gen[] = $person->getID();
                            }
                        }
                    }
                    $directors = $this->storage->getDirectorsOfMovie($directed_movie->getID());
                    if ($directors) {
                        foreach ($directors as $director) {
                            if (!in_array($director->getID(), $this->used_persons)) {
                                $this->used_persons[] = $director->getID();
                                $persons_of_gen[] = $director->getID();
                            }
                        }
                    }
                }
            }
        }
        
        $this->array[1] = $persons_of_gen;
        $this->newGeneration($this->array[1], $persons_of_gen);
        return $this->depth;
    }
    private function newGeneration(&$array, $persons)
    {
        $persons_of_gen = [];
        if ($persons) {
            foreach ($persons as $person) {
                $series = $this->storage->getSeriesOfPerson($person);
                if ($series) {
                    foreach ($series as $s) {
                        if (!in_array($s->getID(), $this->used_series)) {
                            $this->used_series[] = $s->getID();
                            $actors = $this->storage->getActorsOfSeries($s->getID());
                            foreach ($actors as $actor) {
                                if (!in_array($actor->getID(), $this->used_persons)) {
                                    $this->used_persons[] = $actor->getID();
                                    $persons_of_gen[] = $actor->getID();
                                }
                            }
                            $directors = $this->storage->getDirectorsOfSeries($s->getID());
                            if ($directors) {
                                foreach ($directors as $director) {
                                    if (!in_array($director->getID(), $this->used_persons)) {
                                        $this->used_persons[] = $director->getID();
                                        $persons_of_gen[] = $director->getID();
                                    }
                                }
                            }
                        }
                    }
                }
                $movies = $this->storage->getMoviesOfPerson($person);
                if ($movies) {
                    foreach ($movies as $movie) {
                        if (!in_array($movie->getID(), $this->used_movies)) {
                            $this->used_movies[] = $movie->getID();
                            $actors = $this->storage->getActorsOfMovie($movie->getID());
                            if ($actors) {
                                foreach ($actors as $actor) {
                                    if (!in_array($actor->getID(), $this->used_persons)) {
                                        $this->used_persons[] = $actor->getID();
                                        $persons_of_gen[] = $actor->getID();
                                    }
                                }
                            }
                        }
                        $directors = $this->storage->getDirectorsOfMovie($movie->getID());
                        if ($directors) {
                            foreach ($directors as $director) {
                                if (!in_array($director->getID(), $this->used_persons)) {
                                    $this->used_persons[] = $director->getID();
                                    $persons_of_gen[] = $actor->getID();
                                    
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($persons_of_gen) {
            $array[] = $persons_of_gen;
            $this->newGeneration($array[count($array) - 1], $persons_of_gen);
        } else {
            $this->depth = $this->getDepth($this->array, 0);
            // var_dump(highlight_string("<?\n". var_export($this->array, true)));
            // var_dump(highlight_string("<?\n". var_export($this->used_persons, true)));
        }
    }

    private function getDepth($array, $depth)
    {
        
        $looking_for = $_POST['second'];
        foreach ($array as $value) {
            if (!is_array($value)) {
                if ($value == $looking_for) {
                    // echo $depth;
                    return $depth;
                }
            }
        }
        $depth++;
        // var_dump(highlight_string("<?\n". var_export($array, true)));
        if (
            is_array($array[count($array) - 1])
            && !empty($array[count($array) - 1])
        ) {
            return $this->getDepth($array[count($array) - 1], $depth);
        } else {
            return 'existiert nicht';
        }
        
    }

    // ----------------------------------------------------------------------------------------------

    // public function getGenerations($first)
    // {
    //     $this->tree = [];
    //     $this->tree[] = [$first];
    //     $this->used_persons[] = $first;
    //     $series_first = $this->storage->getSeriesOfActor($first);
    //     foreach ($series_first as $s_first) {
    //         $this->used_series[] = $s_first->getID();
    //         $actors = $this->storage->getActorsOfSeries($s_first->getID());
    //         $this->insertGenerations($this->tree, $actors);
    //     }
    //     var_dump(highlight_string("<?\n". var_export($this->tree, true)));

    // }
    // private function insertGenerations(&$tree, $actors)
    // {
    //     $index_counter = 0;
    //     $counter = 0;
    //     foreach ($actors as $actor) {
    //         if (!in_array($actor->getID(), $this->used_persons)) {
    //             $this->used_persons[] = $actor->getID();
    //             $tree[0][1][] = [$actor->getID()];
    //             $series = $this->storage->getSeriesOfActor($actor->getID());
    //             foreach ($series as $s) {
    //                 if (!in_array($s->getID(), $this->used_series)) {
    //                     $this->used_series[] = $s->getID();
    //                     $actors_new = $this->storage->getActorsOfSeries($s->getID());
    //                     // $this->insertGenerations($tree[$index_counter], $actors_new);
    //                     // var_dump(highlight_string("<?\n". var_export($tree[0][1][$counter - 1][0], true)));
    //                     $prev_actor = $tree[0][1][$counter - 1][0];
    //                     // echo $prev_actor;
    //                     $this->insert($tree[0][1][$counter - 1], $actors_new);
    //                     // $this->insert($tree[0][1], $actors_new);

    //                 }
    //             }
    //         }
    //         // $index_counter++;
    //         $counter++;

    //     }
    // }
    // private function insert(&$tree, $actors)
    // {
    //     $counter = 0;
    //     // var_dump(highlight_string("<?\n". var_export($tree, true)));
    //     foreach ($actors as $actor) {
    //         if (!in_array($actor->getID(), $this->used_persons)) {
    //             $this->used_persons[] = $actor->getID();
    //             $tree[1][] = [$actor->getID()];

    //             $series = $this->storage->getSeriesOfActor($actor->getID());
    //             foreach ($series as $s) {
    //                 if (!in_array($s->getID(), $this->used_series)) {
    //                     $this->used_series[] = $s->getID();
    //                     $actors_new = $this->storage->getActorsOfSeries($s->getID());
    //                     // $counter++;

    //                     // echo $s->getTitle();
    //                     // echo $actor->getName();

    //                     // var_dump(highlight_string("<?\n". var_export($tree[1][$counter][0], true)));
                        
    //                     // $this->insert($tree[1][$counter - 1], $actors_new);
    //                     echo $counter;
    //                     var_dump(highlight_string("<?\n". var_export($tree, true)));
    //                     $this->insert($tree[1][$counter], $actors_new);

    //                     // echo $counter;
    //                     $counter++;
    //                 }
    //             }
    //         }
    //         // $counter++;

    //     }

    // }

    // -----------------------------------------------------------------------------------------------------

    // public function getRelation($first, $second)
    // {
    //     if ($first == $second) {
    //         $this->connection = [$first];
    //         return $this->connection;
    //     } else {
    //         $series_first = $this->storage->getSeriesOfActor($first);
    //         $series_second = $this->storage->getSeriesOfActor($second);
    //         $this->used_person_ids[] = $first;
    //         foreach ($series_first as $series) {
    //             $this->compareSeries($first, $series, $series_second, $second);
    //         }
    //         if ($this->connection_found) {
    //             return $this->connection;
    //         } else {
    //             return 'Bacon Nummer nicht vorhanden';
    //         }        
    //     }
    // }

    // private function compareSeries($first, $series, $series_second, $second)
    // {
    //     if (in_array($series, $series_second)) {
    //         if (!in_array($first, $this->connection)) {
    //             array_unshift($this->connection, $first);
    //         }
    //         $this->connection[] = $second;
    //         $this->connection_found = true;
    //         return $this->connection;
    //     } else {
    //         $actors = $this->storage->getActorsOfSeries($series->getID());
    //         foreach ($actors as $actor) {
    //             if (!in_array($actor->getID(), $this->used_person_ids)) {
    //                 $this->used_person_ids[] = $actor->getID();
    //                 $this->connection[] = $actor->getID();
    //                 $this->depth++;
    //                 $series_new = $this->storage->getSeriesOfActor($actor->getID());
    //                 foreach ($series_new as $s_new) {
    //                     if (!in_array($s_new->getID(), $this->used_series_ids)) {
    //                         $this->used_series_ids[] = $s_new->getID();
    //                         $this->compareSeries($first, $s_new, $series_second, $second);
    //                         if ($this->connection_found) {
    //                             break;
    //                         }
    //                         for ($i = 0; $i < $this->depth; $i++) {
    //                             array_pop($this->connection);
    //                         }
    //                         $this->depth = 0;
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // }

    // public function changeArray()
    // {
    //     print_r($this->connection);
    //     for ($i = 0; $i < count($this->connection) - 2; $i++) {
    //         $series_i = $this->storage->getSeriesOfActor($this->connection[$i]);
    //         $this->removePart($i, $series_i);
    //     }
    //     return $this->connection = array_values($this->connection);
    // }
    // public function removePart($index, $series_i)
    // {
    //     for ($j = 2; $j < count($this->connection) - 1; $j++) {
    //         $series_j = $this->storage->getSeriesOfActor($this->connection[$j]);
    //         $result = array_map(
    //             'unserialize',
    //             array_intersect(
    //                 array_map(
    //                     'serialize',
    //                     $series_i
    //                 ),
    //                 array_map(
    //                     'serialize',
    //                     $series_j
    //                 )
    //             )
    //         );
    //         if (empty($result)) {
    //             // return;
    //         } else {
    //             // unset($this->connection[$j]);
    //             array_splice($this->connection, $j - 1, 1);
    //         }
    //     }
    // }
}
