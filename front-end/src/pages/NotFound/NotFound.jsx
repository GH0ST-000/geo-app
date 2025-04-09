import React from 'react';
import NotFountImage from '../../assets/images/404.png';
import './NotFound.css'
import {useTranslation} from "react-i18next";
import {useNavigate} from "react-router-dom";
const NotFound = () => {
    const { t } = useTranslation();
    const navigate = useNavigate();
    return (
        <div style={{minHeight:'600px'}} className='w-100 d-flex flex-column-reverse flex-lg-row justify-content-center align-items-center h-auto mt-5'>
            <div>
                <p className='not-found-oops'>Ooops...</p>
                <h2 className='not-found-text'>{t('pageNotFound')}</h2>

                <div>

                    <p className='not-found-description'>გვერდი რომელზე შესვლა გსურთ ვერ მოიძენა</p>
                </div>

                <button onClick={() => navigate('/')} className='not-found-btn'>{t('home')}</button>
            </div>
            <img className='not-found-image' src={NotFountImage} alt={'notfound'}/>
        </div>
    );
};

export default NotFound;