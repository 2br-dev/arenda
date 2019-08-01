import React from 'react'

import InvoicesView from './views/InvoicesView'

import { BACKEND } from './../path.js'


class Invoices extends React.Component {

    constructor(props) {
        super(props)
        this.state = {
            addError: '',
            invoices: []
        }
        this.getInvoices()
    }

    addSubmit(e) {
        e.preventDefault()
        
    }
        
        
    getInvoices() {
        
        
    }


    render() {
        return (
            <InvoicesView />
        )
    }
}


export default Invoices