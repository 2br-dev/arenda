import React from 'react'
import { connect } from 'react-redux'

import { BACKEND } from './../path'

import './../assets/css/Updater.css'


class Updater extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            visible: 1,
            usersUpdate: 0,
            contractsUpdate: 0,
            invoicesUpdate: 0,
            paymentsUpdate: 0,
            registryUpdate: 0
        }

        this.handleUpdate(props.update)

    }

    handleUpdate(update) {
        if(update !== undefined) {
            this.update(update)
        } else {
            this.setState({visible: 0})
        }
    }


    update(payload) {
        const types = [
            'User',
            'Contract',
            'Invoice',
            'Payment',
            'Registry'
        ]

        if(payload === 'All') {
            types.forEach(type => {   
                const URL = BACKEND + 'get' + type
                const OPTIONS = {
                    mode: 'cors',
                    method: 'POST'
                }
                fetch(URL,OPTIONS)
                    .then(response => response.json())
                    .then(response => {
                        
                        if(type === 'User') {
                            this.props.onUpdateUsers(response)
                        } else if(type === 'Contract') {
                            this.props.onUpdateContracts(response)
                        } else if(type === 'Invoice') {
                            this.props.onUpdateInvoices(response) 
                        } else if(type === 'Payment') {
                            this.props.onUpdatePayments(response)                  
                        } else if(type === 'Registr') {
                            this.props.onUpdateRegistry(response)
                        }
                        this.observer(type, true)
                    })
            })
        } else {
            const URL = BACKEND + 'get' + payload
            const OPTIONS = {
                mode: 'cors',
                method: 'POST'
            }
            fetch(URL,OPTIONS)
                .then(response => response.json())
                .then(response => {
                    if(payload === 'User') {
                        this.props.onUpdateUsers(response)
                    } else if(payload === 'Contract') {
                        this.props.onUpdateContracts(response)
                    } else if(payload === 'Invoice') {
                        this.props.onUpdateInvoices(response) 
                    } else if(payload === 'Payment') {
                        this.props.onUpdatePayments(response)                  
                    } else if(payload === 'Registr') {
                        this.props.onUpdateRegistry(response)
                    }
                    this.observer(payload)
                })
        }
    }


    observer(type, flagAll) {
        if(type === 'User') {
            this.setState({usersUpdate: 1})
        }
        if(type === 'Contract') {
            this.setState({contractsUpdate: 1})
        }
        if(type === 'Invoice') {
            this.setState({invoicesUpdate: 1})
        }
        if(type === 'Payment') {
            this.setState({paymentsUpdate: 1})
        }
        if(type === 'Registry') {
            this.setState({registryUpdate: 1})
        }

        if(flagAll) {
            if(this.state.usersUpdate === 1 && this.state.contractsUpdate === 1 && this.state.invoicesUpdate === 1 && this.state.paymentsUpdate === 1 && this.state.registryUpdate === 1) {
                this.setState({visible: 0})
            }
        } else {
            if(type === 'User') {
                this.setState({visible: 0})
            }
            if(type === 'Contract') {
                this.setState({visible: 0})
            }
            if(type === 'Invoice') {
                this.setState({visible: 0})
            }
            if(type === 'Payment') {
                this.setState({visible: 0})
            }
            if(type === 'Registry') {
                this.setState({visible: 0})
            }
        }
    }

    
    render() {

        const classes = setClass(this.state.visible)
        
        return (
            <div className={classes}>
                <div className="updater__loader">
                    <div className="updater__loader_body">
                        <p className="updater__loader_title">Обновление</p>
                        <div className="updater__loader_line"></div>
                    </div>
                </div>
            </div>
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
    },
})

export default connect(mapStateToProps, mapDispatchToProps)(Updater)


const setClass = (visible) => {
    let temp = 'updater '
    if(visible === 1) {
        temp += 'updater-visible'
    }
    return temp
}