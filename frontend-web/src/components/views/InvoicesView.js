import React, { useState }from 'react'

import DatePicker from 'react-datepicker'
import "react-datepicker/dist/react-datepicker.css";

import './../../assets/css/Invoices.css'


const InvoicesView = props => {
    
    return (
        <div className="content">
            <div className="content__body __center">
                <Add 
                    users={props.users} 
                    contracts={props.contracts} 
                    submit={props.addSubmit} 
                    error={props.addError}
                />
                <List contracts={props.contracts}/>
            </div>
        </div>
    )

}


export default InvoicesView

const Add = ({users, contracts, submit, error}) => {  
    
    const [date, setDate] = useState(new Date())

    const addErrorClass = addError(error)

    return (
        <form className="add" onSubmit={submit}>
            <p className="add__title">Выставить счет</p>
            <div className="add__box">
                <label htmlFor="addSelect" className="add__label">Выберете договор из списка:</label>
                <select id="addContract" className="add__select">
                    {contracts.map(contract =>
                        <option key={`user_${contract.id}`} className="add__select_option" value={contract.id}>{contract.name}</option>
                    )}
                </select>
            </div>
            <div className="add__box">
                <DatePicker
                    selected={date}
                    onChange={setDate}
                    dateFormat="MM/yyyy"
                    showMonthYearPicker
                    className='add__input-date'
                    id="addDate"
                />
            </div>
            <div className="add__box">
                <input id="addPrice" type="text" className="add__input" placeholder="Специальная стоимость"/>
            </div>
            <p className={addErrorClass}>{error}</p>
        </form>
    )
}


const List = (props) => {
    return (
        <section className="list">
            <p className="list__title">Список договоров</p>
            {props.contracts.map(contract => 
                <a key={`contract_${contract.id}`} className="list__link">{contract.name}</a>
            )}
        </section>
    )
}

function addError(value) {
    let temp = 'add__error '
    if(value !== '') {
        temp += 'add__error-visible'
    }
    return temp
} 