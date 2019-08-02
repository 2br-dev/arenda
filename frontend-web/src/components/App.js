import React from 'react';
import { BrowserRouter as Router, Switch, Route } from "react-router-dom";
import { connect } from 'react-redux'

import { getUsers, getContracts, getInvoices, getPayments, getRegistry } from './../Update'
import { resizer } from './../Resizer'

import Registry from './Registry'
import Users from './Users'
import Contracts from './Contracts'
import Invoices from './Invoices'
import Payments from './Payments'
import Header from './Header'

import './../assets/css/reset.css'
import './../assets/fonts/fonts.css'
import './../assets/css/App.css'


class App extends React.Component {
	
	constructor(props) {
		super(props)
		this.state = {}
	}


	componentWillMount() {
		getUsers().then(resolve => {
			this.props.onUpdateUsers(resolve)
		})
		getContracts().then(resolve => {
			this.props.onUpdateContracts(resolve)
		})
		getInvoices().then(resolve => {
			this.props.onUpdateInvoices(resolve)
		})
		getPayments().then(resolve => {
			this.props.onUpdatePayments(resolve)
		})
		getRegistry().then(resolve => {
			this.props.onUpdateRegistry(resolve)
		})
	}


	componentDidMount() {
		/** В зависимости от размера экрана раздает класс .mobile (для :hover) */
		resizer()
	}


	render() {
		const wrapperRegistry = () => <Registry />
		const wrapperUsers     = () => <Users />
		const wrapperContracts = () => <Contracts />
		const wrapperInvoices  = () => <Invoices />
		const wrapperPayments  = () => <Payments />

		return (
			<Router>
				<main className="app">
					<Header />
					<Switch>
						<Route path="/" exact component={wrapperRegistry} />
						<Route path="/users/" component={wrapperUsers} />
						<Route path="/contracts/" component={wrapperContracts} />
						<Route path="/invoices/" component={wrapperInvoices} />
						<Route path="/payments/" component={wrapperPayments} />
					</Switch>
				</main>
			</Router>
		)
	}
}


const mapStateToProps = state => ({})

const mapDispatchToProps = dispatch => ({
	onUpdateUsers: users => {
        dispatch({ type: 'UPDATE_USERS', payload: users })
    },
    onUpdateContracts: contracts => {
        dispatch({ type: 'UPDATE_CONTRACTS', payload: contracts })
    },
    onUpdateInvoices: invoices => {
        dispatch({ type: 'UPDATE_INVOICES', payload: invoices })
    },
    onUpdatePayments: payments => {
        dispatch({ type: 'UPDATE_PAYMENTS', payload: payments })
    },
    onUpdateRegistry: registry => {
        dispatch({ type: 'UPDATE_REGISTRY', payload: registry })
    }
})

export default connect(mapStateToProps, mapDispatchToProps)(App)
