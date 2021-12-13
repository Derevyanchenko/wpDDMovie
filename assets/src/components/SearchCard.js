import React from "react"

const SearchCard = ( {props} ) => {

    const { title, link, thubmnail, vote_average, release_date, genres } = props;

    return(
        <React.Fragment>
            <div className="searchMovies-dropdown__item dropdown-item">
                <a href="#" className="dropdown-item__img">
                    <img src={ thubmnail } alt="" />
                </a>
                <div className="dropdown-item__content">
                    <h4>{ title }</h4>
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
                        <span> { release_date } </span>
                    </div>
                </div>
            </div>
        </React.Fragment>
    );
}

export default SearchCard;