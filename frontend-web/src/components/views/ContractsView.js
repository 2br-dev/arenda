import React, { useState }from 'react'

import DatePicker from 'react-datepicker'
import "react-datepicker/dist/react-datepicker.css";

import './../../assets/css/Contracts.css'


const ContractsView = props => {    
    return (
        <div className="content">
            <div className="content__body __center">
                <Add 
                    users={props.users} 
                    submit={props.addSubmit} 
                    error={props.addError}
                />
                <List contracts={props.contracts}/>
            </div>
        </div>
    )
}


export default ContractsView


const Add = ({users, submit, error}) => {

    const to = new Date()
    const from = new Date(to.getFullYear() + 1, to.getMonth(), to.getDate())
    const [dateTo, setDateTo] = useState(to)
    const [dateFrom, setDateFrom] = useState(from)
    
    
    const addErrorClass = addError(error)


    return (
        <form className="add" onSubmit={submit}>
            <div className="add__box">
                <label htmlFor="addSelect" className="add__label">Выберете пользователя из списка:</label>
                <select id="addSelect" className="add__select">
                    {users.map(user =>
                        <option key={`user_${user.id}`} className="add__select_option" value={user.id}>{user.name}</option>
                    )}
                </select>
            </div>
            <div className="add__box">
                <input id="addName" type="text" className="add__input" placeholder="Наименование договора*"/>
            </div>
            <div className="add__box">
                <p className="add__label">Даты начала и окончания действия договора</p>
                <div className="add__box-date">
                    <DatePicker
                        selected={dateTo}
                        onChange={setDateTo}
                        className='add__input-date'
                        dateFormat="dd-MM-yyyy"
                        id="addDateTo"
                    />
                    <DatePicker
                        selected={dateFrom}
                        onChange={setDateFrom}
                        minDate={dateTo}
                        dateFormat="dd-MM-yyyy"
                        className='add__input-date'
                        id="addDateFrom"
                    />
                </div>
            </div>
            <div className="add__box">
                <input id="addPrice" type="text" className="add__input" placeholder="Стоимость за расчетный период*"/>
            </div>
            <div className="add__box">
                <input id="addDiscount" type="text" className="add__input" placeholder='Процент дисконта (от 0 до 100, дробь через < . >)*'/>
            </div>
            <div className="add__box">
                <input id="addPaymentZone" type="text" className="add__input" placeholder='Длинна платежного периода (с даты выставления счета до конца последнего дня)*'/>
            </div>
            <div className="add__box">
                <input id="addDiscountZone" type="text" className="add__input" placeholder='Длинна дисконтного периода (с даты выставления счета до конца последнего дня)*'/>
            </div>
            <div className="add__box-submit">
                <button className="add__submit">Добавить договор</button>
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