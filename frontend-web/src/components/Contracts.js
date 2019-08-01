import React from 'react'
import { connect } from 'react-redux'

import ContractsView from './views/ContractsView'

import { BACKEND } from './../path.js'


class Contracts extends React.Component {

    constructor(props) {
        super(props)
        this.state = {
            addError: '',
            update: 0
        }
    }


    addSubmit(e) {
        e.preventDefault()
        console.log('submit contracts');
        
    }
        
    render() {
        return (
            <ContractsView
                users={this.props.users}
                contracts={this.props.contracts}
                update={this.state.update}
            />
        )
    }
}


const mapStateToProps = state => ({
    users: state.dashboard.users,
    contracts: state.dashboard.contracts
})

const mapDispatchToProps = dispatch => ({})

export default connect(mapStateToProps, mapDispatchToProps)(Contracts)
