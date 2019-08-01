import React from 'react'
import { connect } from 'react-redux'

import ContractsView from './views/ContractsView'

import { BACKEND } from './../path.js'


class Contracts extends React.Component {

    constructor(props) {
        super(props)
        this.state = {
            addError: '',
            users: this.props.users,
            contracts: []
        }
        this.getContracts()
    }


    addSubmit(e) {
        e.preventDefault()
        console.log('submit contracts');
        
    }
        
        
    getContracts() { 
        const URL = `${BACKEND}getContract`
        const OPTIONS = {
            mode: 'cors',
            method: 'POST'
        }
        
        fetch(URL, OPTIONS)
            .then(response => response.json())
            .then(response => {
                    this.props.onUpdateContracts(response)
                    this.setState({contracts: response})
                })
    }


    render() {
        return (
            <ContractsView
                contracts={this.state.contratcs}
            />
        )
    }
}


const mapStateToProps = state => ({
    users: state.dashboard.users,
    contracts: state.dashboard.contracts
})

const mapDispatchToProps = dispatch => ({
    onUpdateContracts: contracts => {
        dispatch({ type: 'UPDATE_CONTRACTS', payload: contracts })
    }
})

export default connect(mapStateToProps, mapDispatchToProps)(Contracts)
