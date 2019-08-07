import React from 'react'
import { connect } from 'react-redux'

import { View, Text  } from 'react-native';


class App extends React.Component {
	constructor(props) {
		super(props)
		this.state = {}
	}

	render() {
		return (
			<View>
				
			</View >
		)
	}
}


const mapStateToProps = state => ({})
const mapDispatchToProps = dispatch => ({})

export default connect(mapStateToProps, mapDispatchToProps)(App)


