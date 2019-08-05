import React, { useState }from 'react'

import DatePicker from 'react-datepicker'
import "react-datepicker/dist/react-datepicker.css";

import './../../assets/css/Invoices.css'


const InvoicesView = props => {
    
    return (
        <div className="content">
            <div className="content__body __center">
                {/* <Add 
                    users={props.users} 
                    contracts={props.contracts} 
                    submit={props.addSubmit} 
                    error={props.addError}
                /> */}
                {/* <List contracts={props.contracts}/> */}
                <InvoicesList contracts={props.contracts} submit={props.submit} clear={props.clear}/>
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
            <p className="add__title">Выставить счета</p>
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

const InvoicesList = ({contracts, submit, clear}) => {

    const [date, setDate] = useState(new Date())

    return (
        <form className="invoices" onSubmit={submit}>
            <p className="invoices__title">Выставление счетов</p>
            <div className="invoices__date">
                <DatePicker
                    selected={date}
                    onChange={setDate}
                    dateFormat="MMMM yyyy"
                    showMonthYearPicker
                    className='add__input-date'
                    id="date"
                />
            </div>
            {contracts.map(contract =>
                <div className="invoices__item" key={`contract_${contract.id}`}>
                    <input className="invoices__check" type="checkbox" value={contract.id} id={`contract_${contract.id}`} defaultChecked={clear}/>
                    <label htmlFor={`contract_${contract.id}`} className="invoices__name">{contract.name}</label>
                    <input type="text" className="invoices__amount" placeholder="Специальная стоимость" defaultValue={clear ? '' : ''}/>
                </div>
            )}
            <div className="invoices__box-submit">
                <button type="submit" className="invoices__submit">Выставить счета</button>
            </div>
        </form>
    )
}