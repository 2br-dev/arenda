import React from 'react'
import { connect } from 'react-redux'

import { BACKEND } from '../path.js'

import RegistryView from './views/RegistryView'


class Registry extends React.Component {

    constructor(props) {
        super(props)
        this.state = {
            
        }
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

const mapDispatchToProps = dispatch => ({})

export default connect(mapStateToProps, mapDispatchToProps)(Registry)