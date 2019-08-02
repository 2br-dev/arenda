import React from 'react'
import { connect } from 'react-redux'

import { getInvoices } from './../Update'
import InvoicesView from './views/InvoicesView'

import { BACKEND } from './../path.js'


class Invoices extends React.Component {

    constructor(props) {
        super(props)
        this.state = {
            addError: ''
        }
    }

    addSubmit(e) {
        e.preventDefault()
        
    }

    update() {
        getInvoices().then(resolve => {
            this.props.onUpdateInvoices(resolve)
        })
    }
        
    render() {
        return (
            <InvoicesView
                users={this.props.users}
                contracts={this.props.contracts}
                invoices={this.props.invoices}
                addSubmit={this.addSubmit.bind(this)}
                addError={this.state.addError}
            />
        )
    }
}


const mapStateToProps = state => ({
    users: state.dashboard.users,
    contracts: state.dashboard.contracts,
    invoices: state.dashboard.invoices
})

const mapDispatchToProps = dispatch => ({
    onUpdateInvoices: invoices => {
        dispatch({ type: 'UPDATE_INVOICES', payload: invoices })
    }
})

export default connect(mapStateToProps, mapDispatchToProps)(Invoices)