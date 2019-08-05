import React from 'react'
import { connect } from 'react-redux'

import { getInvoices } from './../Update'
import InvoicesView from './views/InvoicesView'

import { BACKEND } from './../path.js'


class Invoices extends React.Component {

    constructor(props) {
        super(props)
        this.state = {}
    }

    submit(e) {
        e.preventDefault()
        
        let date = new Date(document.getElementById('date').value)
        date = `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`
        
        let invoices = []
        document.querySelectorAll('.invoices__item').forEach(item =>{           
            if(item.querySelector('.invoices__check').checked) {
                invoices.push({
                    id:     Number(item.querySelector('.invoices__check').value),
                    amount: Number(item.querySelector('.invoices__amount').value.trim())
                })
            }
        })

        if(invoices.length > 0) {
            invoices = JSON.stringify(invoices)
           
            const Data = new FormData()
            Data.append('date', date)
            Data.append('invoices', invoices)  

            const URL = `${BACKEND}addInvoice`
            const OPTIONS = {
                mode: 'cors',
                method: 'POST',
                body: Data
            }

            fetch(URL, OPTIONS)
                .then(response => response.json())
                .then(response => {
                    if(response === 1) {
                        this.setState({addError: ''})
                        this.update()
                    } else {
                        this.setState({addError: 'Ошибка при занесении в БД'})
                    }                    
                }) 
        }
    }

    update() {
        getInvoices().then(resolve => {
            this.props.onUpdateInvoices(resolve)
        })
    }
        
    render() {
        return (
            <InvoicesView
                contracts={this.props.contracts}
                submit={this.submit.bind(this)}
            />
        )
    }
}


const mapStateToProps = state => ({
    contracts: state.dashboard.contracts
})

const mapDispatchToProps = dispatch => ({
    onUpdateInvoices: invoices => {
        dispatch({ type: 'UPDATE_INVOICES', payload: invoices })
    }
})

export default connect(mapStateToProps, mapDispatchToProps)(Invoices)