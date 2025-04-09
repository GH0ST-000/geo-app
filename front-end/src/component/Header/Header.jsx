
import React from 'react';
import './header.css'
import HeaderInfo from "./HeaderInfo.jsx";
import BurgerBar from '../../assets/images/menu.png'
import Logo from '../../assets/svg/Logo.svg'
import { useTranslation } from "react-i18next";
import {Link, useLocation, useNavigate} from 'react-router-dom';

const Header = () => {
    const { t } = useTranslation();
    const location = useLocation();
    const navigate = useNavigate()
    return (
        <>
            <HeaderInfo />
            <header className='header col-12'>
                <div className="sticky-wrapper">
                <div className='d-flex align-items-center justify-content-between main-header'>
                    <img src={Logo} alt='logo' className='logo' onClick={() => navigate('/')}/>

                    <div className='right-side d-none d-lg-flex align-items-center gap-4'>
                        <nav className="d-flex justify-center items-center">
                            <ul className="d-flex gap-4 p-0 m-0">
                                <li className={location.pathname === '/' ? 'active' : ''}>
                                    <Link to="/">{t("home")}</Link>
                                </li>
                                <li className={location.pathname === '/modules' ? 'active' : ''}>
                                    <Link to="/modules">{t("modules")}</Link>
                                </li>
                                <li className={location.pathname === '/certified-farmers' ? 'active' : ''}>
                                    <Link to="/certified-farmers">{t("certifiedFarmers")}</Link>
                                </li>
                                <li className={location.pathname === '/about' ? 'active' : ''}>
                                    <Link to="/about">{t("aboutUs")}</Link>
                                </li>
                            </ul>
                        </nav>

                        <div className='d-flex align-items-center gap-2'>
                            <button onClick={() => navigate('register')} className='registration-btn'>{t('registration')}</button>
                            <button className='authorization-btn' onClick={() => navigate('authentication')}>{t('authorization')}</button>
                        </div>
                    </div>

                    <div className='d-block d-lg-none'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                             className="bi bi-list" viewBox="0 0 16 16">
                            <path fillRule="evenodd"
                                  d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                        </svg>
                    </div>
                </div>
            </div>
            </header>
        </>

    );
};

export default Header;