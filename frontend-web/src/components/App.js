import React from 'react';
import { BrowserRouter as Router, Switch, Route } from "react-router-dom";

import Dashboard from './Dashboard'
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

		return (
			<Router>
				<main className="app">
					<Header />
					<Switch>
						<Route path="/" exact component={wrapperDashboard} />
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


export default App
