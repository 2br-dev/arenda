import React from 'react'

import Updater from './../Updater'

import './../../assets/css/Contracts.css'


const ContractsView = props => {    
    return (
        <div className="content">
            <div className="content__body __center">
                <Add/>
                <List contracts={props.contracts}/>
            </div>
            {props.update === 1 
                ? <Updater update="Contracts" />
                : null
            }
        </div>
    )
}


export default ContractsView


const Add = (props) => {
    return (
        <p></p>
    )
}


const List = (props) => {
    
    return (
        <section className="list">
            <p className="list__title">Список договоров</p>
            {props.contracts.map(contract => 
                <a className="list__link">{contract.name}</a>
            )}
        </section>
    )
}