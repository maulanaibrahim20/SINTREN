import 'package:dropdown_button2/dropdown_button2.dart';
import 'package:flutter/material.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';

class DropdownButtonComponent<T> extends StatelessWidget {
  final String label;
  final IconData icon;
  final List<DropdownMenuItem<T>> items;
  final T? selectedItem;
  final String hint;
  final String? Function(T?) validator;
  final void Function(T?) onChanged;
  final void Function(T?) onSaved;

  const DropdownButtonComponent({
    super.key,
    required this.label,
    required this.icon,
    required this.items,
    this.selectedItem,
    required this.hint,
    required this.validator,
    required this.onChanged, required this.onSaved,
  });

  @override
  Widget build(BuildContext context) {
    return DropdownButtonFormField2<T>(
      value: selectedItem,
      isExpanded: true,
      isDense: true,
      decoration: InputDecoration(
        filled: true,
        fillColor: ColorTheme().whiteColor,
        isDense: true,
        labelText: label,
        hintText: hint,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(10),
        ),
        prefixIcon: Icon(
          icon,
          color: ColorTheme().primaryColor,
        ),
      ),
      items: items,
      validator: validator,
      onChanged: onChanged,
      buttonStyleData: const ButtonStyleData(
        padding: EdgeInsets.only(right: 8),
      ),
      onSaved: onSaved,
      iconStyleData: const IconStyleData(
        icon: Icon(
          Icons.arrow_drop_down,
          color: Colors.black45,
        ),
        iconSize: 24,
      ),
      dropdownStyleData: DropdownStyleData(
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(15),
        ),
      ),
      menuItemStyleData: const MenuItemStyleData(
        padding: EdgeInsets.symmetric(horizontal: 16),
      ),
    );
  }
}
