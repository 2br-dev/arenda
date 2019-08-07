import React from 'react'
import { connect } from 'react-redux'

import { Router, Stack, Scene, Actions } from 'react-native-router-flux';
import Main from './components/Main'
import Loader from './components/Loader'

import { getUsers, getContracts, getInvoices, getPayments, getRegistry, getRooms } from './Update.js'

import { View, Text, ScrollView  } from 'react-native';



class App extends React.Component {
	constructor(props) {
		super(props)
		this.state = {
			loader: false,
			loadUsers: false,
			loadContracts: false,
			loadInvoices: false,
			loadPayments: false,
			loadRegistry: false,
			loadRooms: false,
			text: ''
		}
	}


	componentWillMount() {
		getUsers().then(resolve => {
			this.props.onUpdateUsers(resolve)
			this.setState({loadUsers: true})
			this.checkLoad()
			// this.setState({text: JSON.stringify(resolve)})
			// this.setState({loader: true})
		})
		// getContracts().then(resolve => {
		// 	this.props.onUpdateContracts(resolve)
		// 	this.setState({loadContracts: true})
		// 	this.checkLoad()
		// })
		// getInvoices().then(resolve => {
		// 	this.props.onUpdateInvoices(resolve)
		// 	this.setState({loadInvoices: true})
		// 	this.checkLoad()
		// })
		// getPayments().then(resolve => {
		// 	this.props.onUpdatePayments(resolve)
		// 	this.setState({loadPayments: true})
		// 	this.checkLoad()
		// })
		// getRegistry().then(resolve => {
		// 	this.props.onUpdateRegistry(resolve)
		// 	this.setState({loadRegistry: true})
		// 	this.checkLoad()
		// })
		// getRooms().then(resolve => {
		// 	this.props.onUpdateRooms(resolve)
		// 	this.setState({loadRooms: true})
		// 	this.checkLoad()
		// })
	}


	checkLoad() {
		if(this.state.loadUsers && this.state.loadContracts && this.state.loadInvoices && this.state.loadPayments && this.state.loadRegistry && this.state.loadRooms) {
			this.setState({loader: true})
		}
	}


	render() {
		if(this.state.loader) {
			return <Main text={this.state.text}/>
		}
		return <Loader/>			
	}
}


const mapStateToProps = state => ({
	users: state.dashboard.users
})
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
    },
	onUpdateRooms: rooms => {
		dispatch({ type: 'UPDATE_ROOMS', payload: rooms })
	}
})

export default connect(mapStateToProps, mapDispatchToProps)(App)


// const Home = props => {
	
// 	const style = {
// 		conteiner: {
// 			backgroundColor: '#ccc',
// 			height: '100%',
// 			flexDirection: 'column',
// 			justifyContent: 'center',
// 			alignItems: 'center'
// 		},
// 		text: {
// 			color: '#333',
// 			fontSize: 30,
// 			fontWeight: 'bold',
// 			marginBottom: 10,
// 			textAlign: 'center',
// 			width: 200,
// 			paddingTop: 10,
// 			paddingBottom: 10,
// 			backgroundColor: '#aaa',
// 			borderRadius: 5
// 		},
// 		textLast: {
// 			marginBottom: 0
// 		}
// 	}
	
// 	return (
// 		<View style={style.conteiner}>
// 			<Text onPress={() => Actions.red()} style={style.text}>Red</Text>
// 			<Text onPress={() => Actions.blue()} style={style.text}>Blue</Text>
// 			<Text onPress={() => Actions.green()} style={[style.text, style.textLast]}>Green</Text>
// 		</View>
// 	)
// }

// <Router>
// 	<Stack key="root">
 // 		<Scene initial key="home" component={Home} title="Home" />
 // 		<Scene key="red" component={WrapRed} title="Red"/>
 // 		<Scene key="blue" component={WrapBlue} title="Blue"/>
 // 		<Scene key="green" component={WrapGreen} title="Green"/>
// 	</Stack>
// </Router>

// const Page = ({color}) => {
	
// 	let bg = '#eee'
// 	if(color === 'red') {
// 		bg = '#ff0000'
// 	} else if(color === 'blue') {
// 		bg = '#0000ff'
// 	} else if(color === 'green') {
// 		bg = '#00ff00'
// 	}

// 	const style = {
// 		conteiner: {
// 			backgroundColor: bg,
// 			paddingTop: 20,
// 		},
// 		text: {
// 			color: '#fff',
// 			fontSize: 40,
// 			fontWeight: 'bold',
// 			marginBottom: 10,
// 			textTransform: 'uppercase',
// 			textAlign: 'center'
// 		},
// 		btn: {
// 			marginLeft: 'auto',
// 			marginRight: 'auto',
// 			color: '#333',
// 			fontSize: 20,
// 			fontWeight: 'bold',
// 			marginBottom: 10,
// 			textAlign: 'center',
// 			width: 200,
// 			paddingTop: 10,
// 			paddingBottom: 10,
// 			borderRadius: 5,
// 			backgroundColor: '#fff',
// 			marginBottom: 20
// 		}
// 	}

// 	return (
// 		<ScrollView style={style.conteiner}>
// 			<Text style={style.text}>{color}</Text>
// 			<Text onPress={() => Actions.home()} style={style.btn}>Back</Text>
// 		</ScrollView>
// 	)
// }
