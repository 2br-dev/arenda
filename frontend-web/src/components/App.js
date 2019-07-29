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


export default App
