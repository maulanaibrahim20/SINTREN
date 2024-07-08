import 'package:dropdown_button2/dropdown_button2.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';

class DropdownButtonComponent<T> extends StatelessWidget {
  final String label;
  final IconData? icon;
  final List<DropdownMenuItem<T>> items;
  final T? selectedItem;
  final String hint;
  final String? Function(T?) validator;
  final void Function(T?) onChanged;
  final void Function(T?) onSaved;

  const DropdownButtonComponent({
    super.key,
    required this.label,
    this.icon,
    required this.items,
    this.selectedItem,
    required this.hint,
    required this.validator,
    required this.onChanged,
    required this.onSaved,
  });

  @override
  Widget build(BuildContext context) {
    return DropdownButtonFormField2<T>(
      value: selectedItem,
      isExpanded: true,
      decoration: InputDecoration(
        contentPadding: EdgeInsets.symmetric(vertical: 15.h, horizontal: 10.w),
        filled: true,
        fillColor: ColorTheme().whiteColor,
        labelText: label,
        labelStyle: TextStyle(fontSize: 14.sp),
        hintText: hint,
        hintStyle: TextStyle(fontSize: 14.sp),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(10.r),
        ),
        prefixIcon: icon == null
            ? null
            : Icon(
                icon,
                color: ColorTheme().primaryColor,
                size: 20.sp,
              ),
      ),
      items: items,
      validator: validator,
      onChanged: onChanged,
      onSaved: onSaved,
      iconStyleData: IconStyleData(
        icon: Icon(
          Icons.arrow_drop_down,
          color: Colors.black45,
          size: 24.sp,
        ),
      ),
      dropdownStyleData: DropdownStyleData(
        maxHeight: 300.h,
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(15.r),
        ),
      ),
      menuItemStyleData: MenuItemStyleData(
        padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 10.h),
      ),
    );
  }
}
