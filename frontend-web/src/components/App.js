import React from 'react';
import { BrowserRouter as Router, Switch, Route } from "react-router-dom";
import { connect } from 'react-redux'


import Dashboard from './Dashboard'
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


	render() {
		const wrapperDashboard = () => <Dashboard />
		const wrapperUsers = () => <Users />
		const wrapperContracts = () => <Contracts />
		const wrapperInvoices = () => <Invoices />
		const wrapperPayments = () => <Payments />

		return (
			<Router>
				<main className="app">
					<Header />
					<Switch>
						<Route path="/" exact component={wrapperDashboard} />
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


/** В зависимости от размера экрана раздает класс .mobile (для :hover) */
window.onload = () => {
	
	const resizer = () => {
		const APP = document.querySelector('.app')
		const NODES = APP.querySelectorAll('*')
		if(window.innerWidth <= 1000) {
			NODES.forEach(node => {
				node.classList.add('mobile')
			})
		} else {
			NODES.forEach(node => {
				node.classList.remove('mobile')
			})
		}
	}
	
	window.addEventListener("resize", resizer)
	resizer()
}


const mapStateToProps = state => ({})

const mapDispatchToProps = dispatch => ({})

export default connect(mapStateToProps, mapDispatchToProps)(App)
