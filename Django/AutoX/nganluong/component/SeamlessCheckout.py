import pycurl
import urllib.parse
import hashlib
from io import BytesIO
from nganluong import config
import json
import xmltodict

class SeamlessCheckout:

    def _call(self, params):
        body            = BytesIO()
        headers         = BytesIO()
        crl = pycurl.Curl()
        crl.setopt(pycurl.URL,              config.NL_ENDPOINT)
        crl.setopt(pycurl.FOLLOWLOCATION,   True)
        crl.setopt(pycurl.AUTOREFERER,      True)
        crl.setopt(pycurl.COOKIESESSION,    True)
        crl.setopt(pycurl.SSL_VERIFYHOST,   False)
        crl.setopt(pycurl.SSL_VERIFYPEER,   False)
        crl.setopt(pycurl.CONNECTTIMEOUT,   60)
        crl.setopt(pycurl.TIMEOUT,          60)
        crl.setopt(pycurl.POST,             True)
        crl.setopt(pycurl.POSTFIELDS,       urllib.parse.urlencode(params))
        crl.setopt(pycurl.WRITEDATA,        body)
        crl.setopt(pycurl.HEADERFUNCTION,   headers.write)
        try:
            crl.perform()
            respone             = body.getvalue().decode("utf-8")
            respone_headers     = headers.getvalue().decode("utf-8")
        except:
            respone             = ''
            respone_headers     = ''

        result                  = str(respone).replace('&', '&amp;')
        data_dict               = xmltodict.parse(result)
        json_result             = json.dumps(data_dict)
        result                  = json.loads(json_result)
        body.close()
        headers.close()
        crl.close()
        return result['result']

    def setExpressCheckout(self, params):
        input                       = {
            'merchant_id'           : config.MERCHANT_ID,
            'merchant_password'     : hashlib.md5(config.MERCHANT_PASSWORD.encode()).hexdigest(), #MERCHANT_PASSWORD
            'version'               : config.VERSION,
            'function'              : 'SetExpressCheckout',
            'receiver_email'        : config.RECEIVER_EMAIL,
            'order_code'            : params['order_code'],
            'total_amount'          : params['total_amount'],
            'payment_method'        : params['payment_method'],
            'payment_type'          : config.payment_type,
            'fee_shipping'          : params['fee_shipping'],
            'bank_code'             : params['bank_code'],
            'return_url'            : config.return_url,
            'buyer_fullname'        : params['buyer_fullname'],
            'buyer_email'           : params['buyer_email'],
            'buyer_mobile'          : params['buyer_mobile'],
            'card_number'           : params['card_number'],
            'card_fullname'         : params['card_fullname'],
            'card_month'            : params['card_month'],
            'card_year'             : params['card_year']
        }

        result                      = self._call(input)
        return result

    def authenTransaction(self, params):
        inputs                      = {
            'merchant_id'           : config.MERCHANT_ID,
            'merchant_password'     : hashlib.md5(config.MERCHANT_PASSWORD.encode()).hexdigest(),
            'version'               : config.VERSION,
            'function'              : 'AuthenTransaction',
            'token'                 : params['token'],
            'otp'                   : params['otp'],
            'auth_url'              : params['auth_url']
        }

        result                      = self._call(inputs)
        return result

    def getTransactionDetail(self, params):
        inputs                      = {
            'merchant_id'           : config.MERCHANT_ID,
            'merchant_password'     : hashlib.md5(config.MERCHANT_PASSWORD.encode()),
            'version'               : config.VERSION,
            'function'              : 'GetTransactionDetail',
            'token'                 : params['token']
        }

        result                      = self._call(inputs)
        return result

    def getRequestField(self, params):
        inputs                      = {
            'merchant_id'           : config.MERCHANT_ID,
            'merchant_password'     : hashlib.md5(config.MERCHANT_PASSWORD.encode()).hexdigest(),
            'version'               : config.VERSION,
            'function'              : 'GetRequestField',
            'receiver_email'        : config.RECEIVER_EMAIL,
            # 'payment_method'        : params['payment_method'],
            # 'bank_code'             : params['bank_code']
        }
        result                      = self._call(inputs)
        return result

    def getBankName(self, bank_code):
        list_bank_name              = {
            'AGB'                   : 'Ngân hàng Nông nghiệp và Phát triển Nông thôn',
            'BAB'                   : 'Ngân hàng TMCP Bắc Á',
            'BIDV'                  : 'Ngân hàng Đầu tư và Phát triển Việt Nam',
            'EXB'                   : 'Ngân hàng TMCP Xuất Nhập Khẩu',
            'MSB'                   : 'Ngân hàng TMCP Hàng Hải',
            'STB'                   : 'Ngân hàng TMCP Sài Gòn Thương Tín',
            'SGB'                   : 'Ngân hàng TMCP  Sài Gòn Công thương',
            'NVB'                   : 'Ngân hàng TMCP Nam Việt',
            'PGB'                   : 'Ngân hàng TMCP Xăng Dầu Petrolimex',
            'GPB'                   : 'Ngân hàng TMCP Dầu Khí',
            'ICB'                   : 'Ngân hàng TMCP Công Thương',
            'TCB'                   : 'Ngân hàng TMCP Kỹ Thương',
            'TPB'                   : 'Ngân hàng TMCP Tiên Phong',
            'VAB'                   : 'Ngân hàng TMCP Việt Á',
            'VIB'                   : 'Ngân hàng TMCP Quốc tế',
            'VCB'                   : 'Ngân hàng TMCP Ngoại Thương Việt Nam',
            'VCBPAY'                : 'VCBPAY',
            'DAB'                   : 'Ngân hàng TMCP Đông Á',
            'MB'                    : 'Ngân hàng TMCP Quân Đội',
            'ACB'                   : 'Ngân hàng TMCP Á Châu',
            'HDB'                   : 'Ngân hàng TMCP Phát Triển Nhà TP.Hồ Chí Minh',
            'VPB'                   : 'Ngân hàng TMCP Việt Nam Thịnh Vượng',
            'OJB'                   : 'Ngân hàng TMCP Đại Dương',
            'SHB'                   : 'Ngân hàng TMCP Sài Gòn - Hà Nội',
            'SEA'                   : 'Ngân hàng TMCP Đông Nam Á',
            'OCB'                   : 'Ngân hàng Phương Đông Việt Nam',
            'ABB'                   : 'Ngân hàng TMCP An Bình',
            'NAB'                   : 'Ngân hàng Nam Á',
            'SCB'                   : 'Ngân hàng TMCP Sài Gòn',
            'IVB'                   : 'Ngân hàng TNHH Indovina',
            'WCP'                   : 'WeChat Pay',
            'VIETTELPOST'           : 'Viettelpost',
        }

        if bank_code in list_bank_name:
            bank_name               = list_bank_name[bank_code]
        else:
            bank_name               = "N/A"

        return bank_name

    def getBanksByPaymentMethod(self, payment_method):

        if (payment_method == "ATM_ONLINE"):
            banks   = [
                'AGB', 'BAB', 'BIDV', 'EXB', 'MSB', 'STB', 'SGB', 'NVB', 'PGB', 'GPB',
                'ICB', 'TCB', 'TPB', 'VAB', 'VIB', 'VCB', 'MB', 'ACB', 'HDB', 'VPB',
                'OJB', 'SHB', 'SEA', 'OCB', 'ABB', 'NAB'
            ]
        elif (payment_method == "IB_ONLINE"):
            banks   = ['BIDV', 'EXB', 'STB', 'ICB', 'TCB', 'VCB', 'DAB', 'ACB']
        elif (payment_method == "QRCODE"):
            banks   = ['AGB', 'MSB', 'NVB', 'ICB', 'VCB', 'VCBPAY', 'MB', 'VPB', 'SHB', 'OCB', 'ABB', 'SCB', 'IVB', 'WCP']
        elif (payment_method == "CASH_IN_SHOP"):
            banks   = ['VIETTELPOST']
        else:
            banks   = []

        bank_info   = {}

        for bank in banks:
            bank_info[bank]     = self.getBankName(bank)

        return bank_info