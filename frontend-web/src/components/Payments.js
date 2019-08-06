import React from 'react'
import { connect } from 'react-redux'

import { getPayments, getRegistry } from './../Update'
import PaymentsView from './views/PaymentsView'

import { BACKEND } from '../path.js'


class Payments extends React.Component {

    constructor(props) {
        super(props)
        this.state = {
            errors: '',
            userId: 1,
            contractId: '',
            contracts: []
        }
    }
    
    
    addSubmit(e) {
        e.preventDefault()      
        
        let errors = []
        let isValid = true
        
        const contractId    = document.getElementById('paymentsContract').value
        const payt         = parseFloat(document.getElementById('peymentsPayt').value.trim())

        let date   = document.getElementById('paymentsDate').value.trim().split('-')
        date = `${date[2]}-${date[1]}-${date[0]}`       
        console.log(contractId);
        
        if(contractId === '') {
            errors.push('Не выбран договор')
            isValid = false
        }
        if(isNaN(payt)) {
            errors.push('Не указана сумма платежа')
            isValid = false
        }

        this.setState({errors: errors.join(' | ')})
        
        const Data = new FormData()
        Data.append('contract_id', contractId)
        Data.append('payt', payt)
        Data.append('date', date)

        
        if(isValid) {           
            const URL = `${BACKEND}addPayment`
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
                        this.clear()
                    } else {
                        this.setState({addError: 'Платеж существует'})
                    }                    
                })   
        }
          
    }

    
    clear() {       
        document.getElementById('peymentsAmount').value = ''
    }


    update() {
        getPayments().then(resolve => {
            this.props.onUpdatePayments(resolve)
        })
        getRegistry().then(resolve => {
			this.props.onUpdateRegistry(resolve)
		}) 
    }


    render() {
        return (
            <PaymentsView 
                users={this.props.users}
                contracts={this.props.contracts}
                errors={this.state.errors}
                submit={this.addSubmit.bind(this)}
            />
        )
    }

}


const mapStateToProps = state => ({
    users: state.dashboard.users,
    rooms: state.dashboard.rooms,
    contracts: state.dashboard.contracts
})

const mapDispatchToProps = dispatch => ({
    onUpdatePayments: payments => {
        dispatch({ type: 'UPDATE_PYMENTS', payload: payments })
    },
    onUpdateRegistry: registry => {
		dispatch({ type: 'UPDATE_REGISTRY', payload: registry })
    }
})

export default connect(mapStateToProps, mapDispatchToProps)(Payments)