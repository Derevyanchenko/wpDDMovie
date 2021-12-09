import React, { useState, useEffect } from 'react';
import axios from 'axios';

const Settings = () => {

    // {
    //     "name": "dd-movie",
    //     "version": "1.0.0",
    //     "main": "index.js",
    //     "dependencies": {
    //       "axios": "^0.21.1",
    //       "react": "^16.7.0",
    //       "react-dom": "^16.7.0"
    //     },
    //     "devDependencies": {
    //       "@babel/preset-es2015": "^7.0.0-beta.53",
    //       "babel-core": "^6.25.0",
    //       "babel-loader": "^7.1.1",
    //       "babel-plugin-transform-class-properties": "^6.24.1",
    //       "babel-plugin-transform-react-jsx": "^6.24.1",
    //       "babel-preset-env": "^1.6.0",
    //       "babel-preset-es2015": "^6.24.1",
    //       "babel-preset-react": "^6.24.1",
    //       "cross-env": "^5.0.1",
    //       "webpack": "^3.12.0",
    //       "webpack-cli": "^3.2.3"
        // },
        // "scripts": {
        //   "test": "echo \"Error: no test specified\" && exit 1",
        //   "build": "cross-env BABEL_ENV=default NODE_ENV=production webpack",
        //   "start": "cross-env BABEL_ENV=default webpack --watch"
        // }
    //   }
      

    // const [ firstname, setFirstName ] = useState( '' );
    // const [ lastname, setLastName ]   = useState( '' );
    // const [ email, setEmail ]         = useState( '' );
    // const [ loader, setLoader ] = useState( 'Save Settings' );

    // const url = `${appLocalizer.apiUrl}/wprk/v1/settings`;

    // const handleSubmit = (e) => {
    //     e.preventDefault();
    //     setLoader( 'Saving...' );
    //     axios.post( url, {
    //         firstname: firstname,
    //         lastname: lastname,
    //         email: email
    //     }, {
    //         headers: {
    //             'content-type': 'application/json',
    //             'X-WP-NONCE': appLocalizer.nonce
    //         }
    //     } )
    //     .then( ( res ) => {
    //         setLoader( 'Save Settings' );
    //     } )
    // }

    // useEffect( () => {
    //     axios.get( url )
    //     .then( ( res ) => {
    //         setFirstName( res.data.firstname );
    //         setLastName( res.data.lastname );
    //         setEmail( res.data.email );
    //     } )
    // }, [] )

    return(
        <React.Fragment>
            <h2>React Settings Form</h2>
            {/* <form id="work-settings-form" onSubmit={ (e) => handleSubmit(e) }>
                <table className="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label htmlFor="firstname">Firstname</label>
                            </th>
                            <td>
                                <input id="firstname" name="firstname" value={ firstname } onChange={ (e) => { setFirstName( e.target.value ) } } className="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label htmlFor="lastname">Lastname</label>
                            </th>
                            <td>
                                <input id="lastname" name="lastname" value={ lastname } onChange={ (e) => { setLastName( e.target.value ) } } className="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label htmlFor="email">Email</label>
                            </th>
                            <td>
                                <input id="email" name="email" value={ email } onChange={ (e) => { setEmail( e.target.value ) } } className="regular-text" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p className="submit">
                    <button type="submit" className="button button-primary">{ loader }</button>
                </p>
            </form> */}
        </React.Fragment>
    )
}

export default Settings;