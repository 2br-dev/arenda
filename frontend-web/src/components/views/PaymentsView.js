import React, { useState } from 'react'

import Complete from './Complete'

import DatePicker from 'react-datepicker'
import "react-datepicker/dist/react-datepicker.css";

import './../../assets/css/Payments.css'


const PaymentsView = props => {
    
    return (
        <div className="content">
            <div className="content__body __center">
                <Add 
                    users={props.users}
                    contracts={props.contracts}
                    errors={props.errors}
                    submit={props.submit}
                />
                <Complete isVisible={props.complete} text={'Платеж успешно добавлен'} />
            </div>
        </div>
    )

}


export default PaymentsView


const Add = ({users, contracts, errors, submit}) => {

    const [userId, setUserId] = useState(0)
    const [date, setDate] = useState(new Date())
    if(users.length !== 0 && userId == 0) {
        setUserId(users[0].id)
    }
    
    const change = function(event) {
        setUserId(event.target.value)
    }

    const addErrorClass = addError(errors)
    
    return(
        <section className="payments">
            <p className="payments__title">Добавить нового пользователя</p>
            <form className="payments__box" id="payments" onSubmit={submit}>
                <div className="payments__box">
                    <label htmlFor="paymentsSelect" className="payments__label">Выберете пользователя из списка:</label>
                    <select id="paymentsUser" className="payments__select" onChange={change}>
                        {users.map(user =>
                            <option key={`user_${user.id}`} className="payments__select_option" value={user.id}>{user.name}</option>
                        )}
                    </select>    
                </div>
                <div className="payments__box">
                    <label htmlFor="paymentsSelect" className="payments__label">Выберете договор из списка:</label>
                    <select id="paymentsContract" className="payments__select">
                        {contracts.map(contract => {
                            if(contract.user_id == userId) {
                                return (<option key={`contract_${contract.id}`} className="payments__select_option" value={contract.id}>{contract.name}</option>)
                            }    
                        })}
                    </select>    
                </div>
                <div className="payments__box">
                <DatePicker
                    selected={date}
                    onChange={setDate}
                    dateFormat="dd-MM-yyyy"
                    className='payments__input-date'
                    id="paymentsDate"
                />  
                </div>
                <div className="payments__box">
                    <input id="peymentsPayt" type="text" className="payments__input" placeholder="Сумма платежа"/>
                </div>
                <div className="payments__box-submit">
                    <button type="submit" className="payments__submit">Добавить платеж</button>
                </div>
                <p className={addErrorClass}>{errors}</p>
            </form>
        </section>
    )
}



function addError(value) {
    let temp = 'payments__error '
    if(value !== '') {
        temp += 'payments__error-visible'
    }
    return temp
}