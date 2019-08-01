import React from 'react'
import { connect } from 'react-redux'

import { BACKEND } from './../path.js'

import DashboardView from './views/DashboradView'


class Dashboard extends React.Component {

    constructor(props) {
        super(props)
        this.state = {
            registryLoading: 1,
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
                this.setState({registryLoading: 0})
            })
    }


    render() {
        return (
            <DashboardView registryLoading={this.state.registryLoading} registry={this.props.registry}/>
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

export default connect(mapStateToProps, mapDispatchToProps)(Dashboard)