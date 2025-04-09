import React from 'react';
import './header.css'
import '@fortawesome/fontawesome-free/css/all.min.css';
import {useTranslation} from "react-i18next";
import LanguageSelector from "../LanguageSelector/LanguageSelector.jsx";

const HeaderInfo = () => {
    const { t } = useTranslation();

    return (
        <div className='header-info-wrapper px-2 d-flex flex-column flex-sm-row justify-content-between py-3 px-4'>
            <div className='d-flex flex-column flex-md-row gap-2'>
                <div>
                    <div className='d-flex align-items-center'>
                        <i className="fa-solid header-info-custom-icon fa-location-dot"></i>
                        <span className='header-info-text px-1'>{t('address')}</span>
                    </div>
                </div>

            </div>
            <div className='d-flex flex-column flex-sm-row gap-1 gap-sm-3 align-items-center'>
                <div className='d-flex gap-3 align-items-center'>
                        <a href="mailto:info@gfa.org.ge" target="_blank" rel="noopener noreferrer">
                            <i className="fa-solid header-info-custom-icon fa-envelope"></i>
                        </a>
                    <i className="fa-brands  header-info-custom-icon fa-facebook-f"></i>
                    <i className="fa-brands  header-info-custom-icon fa-youtube"></i>
                    <i className="fa-brands header-info-custom-icon fa-linkedin-in"></i>
                    <LanguageSelector/>

                </div>



            </div>
        </div>
    )
        ;
};

export default HeaderInfo;