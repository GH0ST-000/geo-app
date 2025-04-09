import React, { useState } from 'react';
import { Checkbox, FormControlLabel, TextField, Button } from '@mui/material';
import { useFormik } from 'formik';
import * as Yup from 'yup';
import './Auth.css';
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";

const Auth = () => {
    const [checked, setChecked] = useState(false);
    const { t } = useTranslation();
    const navigate = useNavigate();

    const formik = useFormik({
        initialValues: {
            email: '',
            password: '',
        },

        onSubmit: (values) => {
            console.log('Login Data:', values);
        },
    });

    return (
        <div className="auth-container">
            <div className="auth-form-container">
                <h3 className='auth-title'>{t('authorization')}</h3>
                <form onSubmit={formik.handleSubmit}>
                    <div className="form-field">
                        <TextField
                            label={t('mail')}
                            variant="filled"
                            className="custom-textfield"
                            {...formik.getFieldProps('email')}
                            error={formik.touched.email && Boolean(formik.errors.email)}
                            helperText={formik.touched.email && formik.errors.email}
                        />
                    </div>

                    <div className="form-field">
                        <TextField
                            label={t("password")}
                            variant="filled"
                            type={checked ? 'text' : 'password'}
                            className="custom-textfield"
                            {...formik.getFieldProps('password')}
                            error={formik.touched.password && Boolean(formik.errors.password)}
                            helperText={formik.touched.password && formik.errors.password}
                        />
                    </div>

                    <div className='checkbox-container'>
                        <FormControlLabel
                            control={
                                <Checkbox
                                    sx={{'&.Mui-checked': {color: '#348e38'}}}
                                    checked={checked}
                                    onChange={() => setChecked(!checked)}
                                />
                            }
                            label={t('showPassword')}
                        />
                    </div>

                    <button type="submit" className='auth-btn'>
                        {t('signIn')}
                    </button>
                    <div className='auth-redirect'>
                        <p className='already-have-account'>
                            {t('dontHaveAnAccount')} <span className='auth-text' onClick={() => navigate('/register')}>{t('registration')}</span>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default Auth;
