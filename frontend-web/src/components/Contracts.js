import React from 'react'
import { connect } from 'react-redux'

import ContractsView from './views/ContractsView'

import { BACKEND } from './../path.js'


class Contracts extends React.Component {

    constructor(props) {
        super(props)
        this.state = {
            addError: '',
            contracts: []
        }
        this.getContracts()
    }


    addSubmit(e) {
        e.preventDefault()
    }
        
        
    getContracts() { 
        // const URL = `${BACKEND}getContracts`
        // const OPTIONS = {
        //     mode: 'cors',
        //     method: 'POST'
        // }
        
        // fetch(URL, OPTIONS)
        //     .then(response => response.json())
        //     .then(response => {
        //             this.props.onUpdateContracts(response)
        //             this.setState({contracts: response})
        //         })
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
    contracts: state.dashboard.contracts
})

const mapDispatchToProps = dispatch => ({
    onUpdateContracts: contracts => {
        dispatch({ type: 'UPDATE_CONTRACTS', payload: contracts })
    }
})

export default connect(mapStateToProps, mapDispatchToProps)(Contracts)
