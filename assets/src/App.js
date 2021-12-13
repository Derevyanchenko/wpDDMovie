import axios from 'axios';
import React, { useState, useEffect } from 'react';
// import Settings from './components/Settings';
import Nav from './components/Nav';
import MovieCard from './components/movieCard';


function App() {
    const [ popularMovies, setPopularMovies ] = useState( [] );
    const [ page, setPage ] = useState( 1 );
    const [ isShowMoreBtnVisible, setShowMoreBtnVisible ] = useState( true );

    const moviesUrl = `${ddMovie.resturl}ddmovie/v1/movies?page=${page}`;

    useEffect( () => {
        axios.get( moviesUrl )
        .then( (res) => {
            setPopularMovies( res.data );
            setPage( page => (page + 1) );
        } )
    }, [] )

    function loadMoviesHandle() {
        axios.get( moviesUrl )
        .then( (res) => {
            let { data } = res;

            if ( data !== '' && data !== undefined && data !== null ) {
                setPage( page => (page + 1) );
                setPopularMovies( popularMovies => ([...popularMovies, ...data]) )
            } else {
                setShowMoreBtnVisible( false )
            }
        } )
    }

    // return
    return(
        <React.Fragment>
            <Nav />

            <div className="container mx-auto px-4 pt-16">
                <div className="popular-movies">
                    <h2 className="uppercase tracking-wider text-orange-500 text-lg font-semibold">Popular Movies</h2>
                    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
                        { popularMovies &&
                            popularMovies.map( (movie, index) => (
                                <MovieCard 
                                    data={ movie }
                                />
                            ) )
                        }
                    </div>

                    { isShowMoreBtnVisible && (
                        <button className="loadMore" onClick={ loadMoviesHandle }>Load more</button>
                    ) }

                </div> 
            </div>

        </React.Fragment>
    )
}
export default App;