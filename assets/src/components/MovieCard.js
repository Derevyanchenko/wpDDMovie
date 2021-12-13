import React from "react";

const MovieCard = ( props ) => {

    const { title, thubmnail, release_date, vote_average, genres } = props.data;

    return(
        <React.Fragment>
            <div className="mt-8">
                <a href=" route('movies.show', $movie['id']) ">
                    <img src={ thubmnail } alt="poster" className="hover:opacity-75 transition ease-in-out duration-150" />
                </a>
                <div className="mt-2">
                    <a href=" route('movies.show', $movie['id']) " className="text-lg mt-2 hover:text-gray-300">
                        { title }
                    </a>
                    <div className="text-gray-400 text-sm">
                        { genres &&
                            genres.map( (genre, index) => (
                                index > 0 ? ', ' + genre.name : genre.name
                            ) )
                        }
                    </div>
                    <div className="flex items-center text-gray-400 text-sm mt-1">
                        <svg className="fill-current text-orange-500 w-4" viewBox="0 0 24 24"><g data-name="Layer 2"><path d="M17.56 21a1 1 0 01-.46-.11L12 18.22l-5.1 2.67a1 1 0 01-1.45-1.06l1-5.63-4.12-4a1 1 0 01-.25-1 1 1 0 01.81-.68l5.7-.83 2.51-5.13a1 1 0 011.8 0l2.54 5.12 5.7.83a1 1 0 01.81.68 1 1 0 01-.25 1l-4.12 4 1 5.63a1 1 0 01-.4 1 1 1 0 01-.62.18z" data-name="star"/></g></svg>
                        <span className="ml-1"> { vote_average } </span>
                        <span className="mx-2">|</span>
                        <span>{ release_date }</span>
                    </div>
                </div>
            </div>
            {/* movie card */}
        </React.Fragment>
    )
}

export default MovieCard;

