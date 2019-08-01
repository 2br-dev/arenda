import React from 'react'
import { connect } from 'react-redux'

import { BACKEND } from '../path.js'

import RegistryView from './views/RegistryView'


class Registry extends React.Component {

    constructor(props) {
        super(props)
        this.state = {
            
        }
        
        this.getRegistry()

    }


    getRegistry() {
        const URL = `${BACKEND}getRegistry`
        const OPTIONS = {
            mode: 'cors',
            method: 'POST'
        }

        fetch(URL,OPTIONS)
            .then(response => response.json())
            .then(response => {
                this.props.onUpdateRegistry(response)
            })
    }


    render() {
        return (
            <RegistryView registry={this.props.registry}/>
        )
    }
}


const mapStateToProps = state => ({
    registry: state.dashboard.registry
})

const mapDispatchToProps = dispatch => ({
    onUpdateRegistry: (registry) => {
        dispatch({ type: 'UPDATE_REGISTRY', payload: registry })
    }
})

export default connect(mapStateToProps, mapDispatchToProps)(Registry)