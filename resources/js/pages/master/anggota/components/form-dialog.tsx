import Combobox from '@/components/combobox'
import ComboboxDinamis from '@/components/combobox-dinamis'
import FormCalendar from '@/components/form-calendar'
import FormInput from '@/components/form-input'
import FormInputCurrency from '@/components/form-input-currency'
import { Button } from '@/components/ui/button'
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle
} from '@/components/ui/dialog'
import axios from 'axios'
import { Loader2, Save } from 'lucide-react'
import { useEffect, useState } from 'react'
type props = {
    open: boolean
    setOpen: (open: boolean) => void
    title: string
    data: any
    setData: (data: any) => void
    errors: any
    formRefs: React.RefObject<Record<string, HTMLInputElement | null>>
    processing: boolean
    handleForm: (e: React.FormEvent) => void
    isEdit: boolean | false
}& Record<string, any>
const statusKepegawaian = [
    {
        'value' : 'ASN',
        'label' : 'ASN'
    },
    {
        'value' : 'NON ASN',
        'label' : 'NON ASN'
    },
    ]
export default function FormDialog({ open, setOpen, title, data, setData, errors, formRefs, processing, handleForm, isEdit, ...props }: props) {
    const [jenisTabungan, setJenisTabungan] = useState("");
    const [satuanKerja, setSatuanKerja] = useState("");
    useEffect(() => {
        setJenisTabungan("")
        setSatuanKerja("")
    }, [open])
    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogContent className='w-6xl'>
                <form onSubmit={handleForm}>
                    <DialogHeader>
                        <DialogTitle>Form {title}</DialogTitle>
                        <DialogDescription className='italic'>"Silakan isi formulir di bawah ini dengan lengkap dan benar"</DialogDescription>
                    </DialogHeader>
                    <div className="grid grid-cols-3 gap-2 mt-5 mb-2">
                        <FormInput
                            id="nik"
                            type="text"
                            value={data.nik}
                            onChange={(e) => setData((prevData: any) => ({ ...prevData, nik: e.target.value }))}
                            inputRef={(el) => {if (formRefs.current) {formRefs.current['nik'] = el}}}
                            placeholder="Masukkan nik"
                            error={errors.nik}
                            autoComplete='off'
                            maxLength={16}
                            minLength={16}
                        />
                        <FormInput
                            id="nama"
                            type="text"
                            value={data.nama}
                            onChange={(e) => setData((prevData: any) => ({ ...prevData, nama: e.target.value }))}
                            inputRef={(el) => {if (formRefs.current) {formRefs.current['nama'] = el}}}
                            placeholder="Masukkan nama"
                            error={errors.nama}
                            autoFocus
                            required
                        />
                        <Combobox label="statusKepegawaian" selectedValue={data.statusKepegawaian} options={statusKepegawaian} onSelect={(value) => setData((prevData:any) => ({ ...prevData, statusKepegawaian: value }))} error={errors.statusKepegawaian} />
                        { !isEdit && <>
                            <ComboboxDinamis
                                label="jenisTabungan"
                                selectedValue={data.jenisTabungan}
                                fetchOptions={async (search) => {
                                    const { data: options } = await axios.post(route('jenis-tabungan.list'), {id: data.jenisTabungan,search});
                                    if (!options.find((d: any) => d.value === data.jenisTabungan) && search !== '') {
                                        setData((prev: any) => ({ ...prev, jenisTabungan: '' }));
                                    }
                                    return options;
                                }}
                                onSelect={(value, label) => {setData((prev: any) => ({ ...prev, jenisTabungan: value })); setJenisTabungan(label)}}
                                search={jenisTabungan}
                                setSearch={setJenisTabungan}
                                error={errors.jenisTabungan}
                                className="mt-2"
                            />
                            <FormCalendar
                                id="tanggal"
                                value={data.tanggal}
                                onChange={(e) => setData((prevData: any) => ({ ...prevData, tanggal: e }))}
                                inputRef={(el) => {if (formRefs.current) {formRefs.current['tanggal'] = el}}}
                                placeholder="Pilih tanggal"
                                error={errors.tanggal}
                                required
                                autoComplete='off'
                                tanggalSelanjutnya={false}
                            />
                            <FormInputCurrency
                                id="nominal"
                                type="text"
                                value={data.nominal}
                                onChange={(value, floatValue) => setData((prevData: any) => ({ ...prevData, nominal: floatValue }))}
                                inputRef={(el) => {if (formRefs.current) {formRefs.current['nominal'] = el}}}
                                placeholder="Masukkan nominal"
                                error={errors.nominal}
                                autoComplete='off'
                                required
                            />
                        </>
                        }
                    </div>
                    <ComboboxDinamis
                        label="satuanKerja"
                        selectedValue={data.satuanKerja}
                        fetchOptions={async (search) => {
                            const { data: options } = await axios.post(route('satuan-kerja.list'), {id: data.satuanKerja,search});
                            if (!options.find((d: any) => d.value === data.satuanKerja) && search !== '') {
                                setData((prev: any) => ({ ...prev, satuanKerja: '' }));
                            }
                            return options;
                        }}
                        onSelect={(value, label) => {setData((prev: any) => ({ ...prev, satuanKerja: value })); setSatuanKerja(label)}}
                        search={satuanKerja}
                        setSearch={setSatuanKerja}
                        error={errors.satuanKerja}
                        className="mt-2"
                    />
                    <DialogFooter>
                        <div className="flex items-center mt-5">
                            <Button type="submit" disabled={processing}> {processing ? <Loader2 className="animate-spin" /> : <Save /> } Simpan</Button>
                        </div>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}
