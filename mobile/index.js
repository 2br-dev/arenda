import React from 'react'
import {AppRegistry} from 'react-native';

import { createStore } from 'redux'
import { Provider } from 'react-redux';
import reducer from './reducers'

import App from './App';
import {name as appName} from './app.json';


const store = createStore(
    reducer
)


const RNRedux = () => {
    return (
        <Provider store = { store }>
            <App />
        </Provider>
    )
}


AppRegistry.registerComponent(appName, () => RNRedux);
