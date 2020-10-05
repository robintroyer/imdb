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

    private $nodes;
    private $curl_arr;
    private $results;
    private $node_count;

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

        $this->nodes = [];
        $this->curl_arr = [];
        $this->results = [];
        $this->node_count = 0;
    }
    public function theMovieDBForm()
    {
        echo '<form method="get">';
        echo '<input type="text" name="actor_name">';
        echo '<input type="submit">';
        echo '</form>';
        // unset($_GET['actor_submit']);
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
            echo '
                <div class="album py-5 bg-light">
                    <div class="container">
                        <div class="row">';
            foreach ($movies->cast as $movie) {
                echo '<div class="card" style="width: 18 rem;">';
                echo '<img style="width: 200px;" src="https://image.tmdb.org/t/p/original' . $movie->poster_path . '" alt="' . $movie->title . '">';
                echo '<div class="card-body">';
                echo '<h5>' . $movie->title . '</h5>';
                unset($_GET['actor_name']);
                echo '<form method="get">';
                echo '<input type="submit" value="Details">
                <input type="hidden" name="movie" value="' . $movie->id . '">';
                echo '</form>';
                echo '</div></div>';
            }
            echo '</div></div></div>';
            $series = file_get_contents('https://api.themoviedb.org/3/person/'
            . $cont->id . '/tv_credits?api_key=906d85a2d561a1998026d96bbee93f3d&language=en-US');
            $series = json_decode($series);
            echo '<br /><h3>Serien:</h3>';
            echo '
                <div class="album py-5 bg-light">
                    <div class="container">
                        <div class="row">';
            foreach ($series->cast as $s) {
                echo '<div class="card" style="width:18rem;">';
                echo '<img style="width: 200px;" src="https://image.tmdb.org/t/p/original' . $s->poster_path . '" alt="' . $s->name . '">';
                echo '<div class="card-body">';
                unset($_GET['actor_name']);
                echo '<h5>' . $s->name . '</h5>';
                echo '<form method="get">';
                echo '<input type="submit" value="Details">
                <input type="hidden" name="series" value="' . $s->id . '">';
                echo '</form>';
                echo '</div></div>';
            }
            echo '</div></div></div>';
           
            break;
        }

        
    }

    public function showMovie($movie)
    {
        $details = file_get_contents('https://api.themoviedb.org/3/movie/' . $movie . '?api_key=906d85a2d561a1998026d96bbee93f3d&language=en-US');
        // print_r($details);
        $details = json_decode($details);
        echo '<h1>' . $details->original_title . '</h1>';
        echo '<img height="300px" width="auto" alt="' . $details->original_title . '" src="https://image.tmdb.org/t/p/original' . $details->poster_path . '">';
        $cast = file_get_contents('https://api.themoviedb.org/3/movie/' . $movie . '/credits?api_key=906d85a2d561a1998026d96bbee93f3d');
        $cast = json_decode($cast);
        echo '<h3>Schauspieler:</h3>';
        echo '
        <div class="album py-5 bg-light">
            <div class="container">
                <div class="row">';
        foreach ($cast->cast as $c) {
            echo '<div class="card" style="width: 18 rem;">';
            echo '<img style="width: 200px;" src="https://image.tmdb.org/t/p/original' . $c->profile_path . '" alt="' . $c->name . '">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $c->name . '</h5>';
            echo '<form method="get">';
            echo '<input class="btn btn-primary" value="Details" type="submit">';
            echo '<input type="hidden" name="actor_id" value="' . $c->id . '">';
            echo '</form>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div></div></div>';
    }
    public function showSeries($series)
    {
        $details = file_get_contents('https://api.themoviedb.org/3/tv/' . $series . '?api_key=906d85a2d561a1998026d96bbee93f3d&language=en-US');
        $details = json_decode($details);
        echo $details->number_of_seasons;
        echo '<br />';
        echo $details->number_of_episodes;
        echo '<h1>' . $details->name . '</h1>';
        echo '<img height="300px" width="auto" alt="' . $details->name . '" src="https://image.tmdb.org/t/p/original' . $details->poster_path . '">';
        $cast = [];
        for ($i = 1; $i <= $details->number_of_seasons; $i++) {
            $season = file_get_contents('https://api.themoviedb.org/3/tv/' . $series . '/season/'
            . $i . '?api_key=906d85a2d561a1998026d96bbee93f3d&language=en-US');
            $season = json_decode($season); 
            for ($j = 1; $j <= count($season->episodes); $j++) {
                $episode_cast = file_get_contents('https://api.themoviedb.org/3/tv/'
                . $series . '/season/' . $i . '/episode/' . $j . '/credits?api_key=906d85a2d561a1998026d96bbee93f3d&language=en-US');
                $episode_cast = json_decode($episode_cast);
                foreach ($episode_cast->cast as $episode_c) {
                    $cast[] = $episode_c;
                }
                foreach ($episode_cast->guest_stars as $guest) {
                    $cast[] = $guest;
                }
            }
            
        }
        $cast2 = [];
        $used_cast_ids = [];
        foreach ($cast as $c) {
            if (!in_array($c->id, $used_cast_ids)) {
                $used_cast_ids[] = $c->id;
                $cast2[] = $c;
            }
        }
        $cast = $cast2;
        echo '<h3>Schauspieler:</h3>';
        echo '
        <div class="album py-5 bg-light">
            <div class="container">
                <div class="row">';
        foreach ($cast as $c) {
            echo '<div class="card" style="width: 18 rem;">';
            echo '<img style="width: 200px;" src="https://image.tmdb.org/t/p/original' . $c->profile_path . '" alt="' . $c->name . '">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $c->name . '</h5>';
            echo '<form method="get">';
            echo '<input class="btn btn-primary" value="Details" type="submit">';
            echo '<input type="hidden" name="actor_id" value="' . $c->id . '">';
            echo '</form>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div></div></div>';
    }

    public function objectInArray($needle, $haystack)
    {
        foreach ($haystack as $h) {
            if ($needle === $h->id) {
                return true;
            }
        }
        return false;
    }

    public function getActorName($id)
    {
        $actor = file_get_contents('https://api.themoviedb.org/3/person/' . $id . '?api_key=906d85a2d561a1998026d96bbee93f3d&language=en-US');
        $actor = json_decode($actor);
        // print_r($actor);
        return $actor->name;
    }


    public function showForm()
    {
        // echo '<form method="post">';
        // echo '<select name="first">';
        // $persons = $this->storage->getPersons();
        // foreach ($persons as $person) {
        //     echo '<option value="' . $person->getID() . '">' . $person->getName() . '</option>';
        // }
        // echo '</select>';
        // echo '<select name="second">';
        // foreach ($persons as $person) {
        //     echo '<option value="' . $person->getID() . '">' . $person->getName() . '</option>';
        // }
        // echo '</select>';
        // echo '<input type="submit" name="submit_bacon">';
        // echo '</form>';

        echo '<form method="post">';
        echo '<input type="text" name="first">';
        echo '<input type="text" name="second">';
        echo '<input type="submit" name="submit_bacon">';
        echo '<input type="submit" name="test" value="TEST">';
        echo '</form>';
    }

    public function getBacon()
    {
        $first = $_POST['first'];
        $second = $_POST['second'];
        $used_actors = [];
        $used_movies = [];
        $counter = 0;
        $all_movies_casts = [];
        $context = stream_context_create(['http' => ['header' => 'Connection: close\r\n']]);

        if ($first == $second) {
            return $counter;
        } else {
            $counter++;
        }

        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, 'https://api.themoviedb.org/3/search/person?api_key=906d85a2d561a1998026d96bbee93f3d&query=' . urlencode($first));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $person = curl_exec($ch);
        $person = json_decode($person);
        curl_close($ch);

        // $person = file_get_contents('https://api.themoviedb.org/3/search/person?api_key=906d85a2d561a1998026d96bbee93f3d&query=' . urlencode($first), false, $context);
        // $person = json_decode($person);
        
        // print_r($person);
        foreach ($person->results as $p) {
            $person_id = $p->id;
            // echo $person_id;
            $used_actors[] = $person_id;
            break;
        }

        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, 'https://api.themoviedb.org/3/search/person?api_key=906d85a2d561a1998026d96bbee93f3d&query=' . urlencode($second));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $second_person = curl_exec($ch);
        $second_person = json_decode($second_person);
        curl_close($ch);

        // $second_person = file_get_contents('https://api.themoviedb.org/3/search/person?api_key=906d85a2d561a1998026d96bbee93f3d&query=' . urlencode($second), false, $context);
        // $second_person = json_decode($second_person);
        foreach ($second_person->results as $second_p) {
            $second_person_id = $second_p->id;
            // echo $second_person_id;
            break;
        }

        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, 'https://api.themoviedb.org/3/person/'
        . $person_id . '/movie_credits?api_key=906d85a2d561a1998026d96bbee93f3d');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $movies_of_person = curl_exec($ch);
        $movies_of_person = json_decode($movies_of_person);
        curl_close($ch);

        // $movies_of_person = file_get_contents('https://api.themoviedb.org/3/person/'
        // . $person_id . '/movie_credits?api_key=906d85a2d561a1998026d96bbee93f3d', false, $context);
        // $movies_of_person = json_decode($movies_of_person);

        foreach ($movies_of_person->cast as $movie_of_person) {
            $movie_id = $movie_of_person->id;
            if (!in_array($movie_id, $used_movies)) {
                $used_movies[] = $movie_id;
                $ch = curl_init();
                $timeout = 5;
                curl_setopt($ch, CURLOPT_URL, 'https://api.themoviedb.org/3/movie/' . $movie_id . '/credits?api_key=906d85a2d561a1998026d96bbee93f3d');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $movie_cast = curl_exec($ch);
                $movie_cast = json_decode($movie_cast);
                curl_close($ch);
                // $movie_cast = file_get_contents('https://api.themoviedb.org/3/movie/' . $movie_id . '/credits?api_key=906d85a2d561a1998026d96bbee93f3d', false, $context);
                // $movie_cast = json_decode($movie_cast);
                foreach ($movie_cast->cast as $cast) {
                    if (!in_array($cast->id, $used_actors)) {
                        $used_actors[] = $cast->id;
                        $all_movies_casts[] = $cast;
                        // echo $cast->id . '<br />';
                        // echo $second_person_id . '<br />';
                        // echo $cast->id . '<br />';
                        if ($second_person_id == $cast->id) {
                            return $counter;
                        }
                    }
                }
            }
        }
        // print_r($all_movies_casts);
        $test_counter = 0;
        $start = microtime(true);

        // $nodes = [];

        // print_r($all_movies_casts);
        
        foreach ($all_movies_casts as $cast) {
            $this->nodes[] = 'https://api.themoviedb.org/3/person/' . $cast->id . '/movie_credits?api_key=906d85a2d561a1998026d96bbee93f3d';
            // echo $cast->name . '<br />';
        }
        // echo '<br />';
        // echo count($this->nodes);
        // echo '<br />';
        // $this->node_count = count($this->nodes);
        $this->nextGen($second_person_id, $counter);

        // print_r($nodes);
        // $node_count = count($this->nodes);
        // $curl_arr = [];
        // $results = [];
        // $master = curl_multi_init();
        // for ($i = 0; $i < $node_count; $i++) {
        //     $url = $this->nodes[$i];
        //     $curl_arr[$i] = curl_init($url);
        //     curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
        //     curl_multi_add_handle($master, $curl_arr[$i]);
        // }
        // do {
        //     curl_multi_exec($master, $running);
        // } while ($running > 0);
        // for ($i = 0; $i < $node_count; $i++) {
        //     $results[] = curl_multi_getcontent($curl_arr[$i]);
        // }
        // print_r($results);

        echo microtime(true) - $start;
        if ($counter) {
            return $counter;
        } else {
            return 'Keine Bacon Nummer gefunden.';
        }
        
    }

    // public function test()
    // {
        // $fp = fsockopen('https://api.themoviedb.org/3/person/73968/movie_credits?api_key=906d85a2d561a1998026d96bbee93f3d', 80, $errno, $errstr, 30);
        // if (!$fp) {
        //     echo "$errstr ($errno)<br />\n";
        // } else {
        //     $out = "GET / HTTP/1.1\r\n";
        //     $out .= "Host: http://localhost/imdb/home.php\r\n";
        //     $out .= "Connection: Close\r\n\r\n";
        //     fwrite($fp, $out);
        //     while (!feof($fp)) {
        //         echo fgets($fp, 128);
        //     }
        //     fclose($fp);
        // }






        // $ch = curl_init();
        // $timeout = 5;
        // curl_setopt($ch, CURLOPT_URL, 'https://api.themoviedb.org/3/person/73968/movie_credits?api_key=906d85a2d561a1998026d96bbee93f3d');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        // $data = curl_exec($ch);
        // $data = json_decode($data);
        // // curl_close($ch);

        // $data = json_decode($data);
        // print_r($data);
    // }

    public function nextGen($second_id, &$counter)
    {

        $node_count = count($this->nodes);
        $curl_arr = [];
        $results = [];
        $master = curl_multi_init();
        for ($i = 0; $i < $node_count; $i++) {
            $url = $this->nodes[$i];
            $curl_arr[$i] = curl_init($url);
            curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
            curl_multi_add_handle($master, $curl_arr[$i]);
        }
        do {
            curl_multi_exec($master, $running);
        } while ($running > 0);
        for ($i = 0; $i < $node_count; $i++) {
            $results[] = curl_multi_getcontent($curl_arr[$i]);
        }
        // $results = json_decode($results);
        print_r($results);
        $nodes = [];
        // foreach ($results as $result) {
        //     foreach ($result->cast as $res) {
        //         echo $res->id . '<br />';
        //     }
        //     // echo $result->cast->id . '<br />';
        // //     $nodes[] = 'https://api.themoviedb.org/3/movie/{movie_id}/credits?api_key=906d85a2d561a1998026d96bbee93f3d';
        // }
        // echo '<br />';
        // echo count($results);
        // echo '<br />';
        // print_r($results);
        // $context = stream_context_create(['http' => ['header' => 'Connection: close\r\n']]);

        // request token: 044699e61f3fd456f85c43583b6899621eb780bd

        // echo $counter;
        // $ch = curl_init();
        // $timeout = 5;
        // curl_setopt($ch, CURLOPT_URL, 'https://api.themoviedb.org/3/person/' . $id . '/movie_credits?api_key=906d85a2d561a1998026d96bbee93f3d');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        // $movies = curl_exec($ch);
        // $movies = json_decode($movies);
        // curl_close($ch);
        
        // $movies = file_get_contents('https://api.themoviedb.org/3/person/' . $id . '/movie_credits?api_key=906d85a2d561a1998026d96bbee93f3d', false, $context);
        // $movies = json_decode($movies);
        // print_r($movies);
        // echo '<br /><br /><br /><br /><br /><br />';
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
        // var_dump(highlight_string("<?\n". var_export($this->array, true)));
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
