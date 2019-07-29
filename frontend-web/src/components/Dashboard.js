import React from 'react'
import { connect } from 'react-redux'

import DashboardView from './views/DashboradView'


class Dashboard extends React.Component {

    constructor(props) {
        super(props)
        this.state = {
            registryLoading: 1
        }
    }


    getRegistry() {
        const URL = 'http://arenda/backend/getRegistry.php'
        const OPTIONS = {
            method: 'POST',
            body: {}
        }

        // fetch(URL, OPTIONS)
        //     .then(response => response.json())
        //     .then(response => {
        //         console.log(response)
        //     })
    }


    render() {
        return (
            <DashboardView registryLoading={this.state.registryLoading}/>
        )
    }
}


const mapStateToProps = state => ({
    registry: state.dashboard.registry
})

const mapDispatchToProps = dispatch => ({
    onSetRegistry: (registry) => {
        dispatch({ type: 'SET_REGISTORY', payload: registry })
    }
})

export default connect(mapStateToProps, mapDispatchToProps)(Dashboard)